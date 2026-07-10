<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PhysicalDocument;
use App\Models\DocumentAuditLog;
use App\Models\Tki;

class DocumentTracker extends Component
{
    use WithPagination;
    use \Livewire\WithFileUploads;

    public $search = '';
    
    public $perPage = 10;
    public $sortBy = 'tanggal_daftar';
    public $sortDirection = 'desc';

    public function sortByField($field)
    {
        $allowed = ['tanggal_daftar', 'nama', 'tanggal_lahir', 'tempat_lahir', 'negara_tujuan', 'nama_sponsor'];
        if (!in_array($field, $allowed)) return;

        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    // Add/Edit Document Modal
    public $showAddModal = false;
    public $showEditModal = false;
    public $tki_id = '';
    public $document_type = '';
    public $custom_document_type = '';
    public $proof_file;
    public $edit_document_id;
    
    // Transfer Modal
    public $showTransferModal = false;
    public $transferDocumentId = null;
    public $newLocation = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openAddModal($tkiId)
    {
        $this->tki_id = $tkiId;
        $this->showAddModal = true;
    }

    public function editDocument($id)
    {
        $doc = PhysicalDocument::findOrFail($id);
        $this->edit_document_id = $doc->id;
        $this->tki_id = $doc->tki_id;
        
        $standardTypes = ['Paspor', 'KTP', 'Kartu Keluarga', 'Akte Kelahiran', 'Buku Nikah', 'Ijazah'];
        if (in_array($doc->document_type, $standardTypes)) {
            $this->document_type = $doc->document_type;
            $this->custom_document_type = '';
        } else {
            $this->document_type = 'Lainnya';
            $this->custom_document_type = $doc->document_type;
        }
        
        $this->showEditModal = true;
    }

    public function updateDocument()
    {
        $this->validate([
            'document_type' => 'required',
            'custom_document_type' => 'required_if:document_type,Lainnya',
            'proof_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $doc = PhysicalDocument::findOrFail($this->edit_document_id);
        $finalType = $this->document_type === 'Lainnya' ? $this->custom_document_type : $this->document_type;
        
        $filePath = $doc->file_path;
        if ($this->proof_file) {
            $filePath = $this->proof_file->store('documents', 'public');
        }

        $doc->update([
            'document_type' => $finalType,
            'file_path' => $filePath,
        ]);

        DocumentAuditLog::create([
            'physical_document_id' => $doc->id,
            'user_id' => auth()->id(),
            'action_taken' => 'Document Edited',
            'previous_location' => $doc->current_location,
            'new_location' => $doc->current_location,
        ]);

        $this->reset(['document_type', 'custom_document_type', 'proof_file', 'edit_document_id', 'showEditModal']);
        session()->flash('success', 'Document updated successfully.');
    }

    public function saveDocument()
    {
        $this->validate([
            'tki_id' => 'required',
            'document_type' => 'required',
            'custom_document_type' => 'required_if:document_type,Lainnya',
            'proof_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $finalType = $this->document_type === 'Lainnya' ? $this->custom_document_type : $this->document_type;

        $filePath = null;
        if ($this->proof_file) {
            $filePath = $this->proof_file->store('documents', 'public');
        }

        $doc = PhysicalDocument::create([
            'tki_id' => $this->tki_id,
            'document_type' => $finalType,
            'current_location' => 'Office',
            'status' => 'Initial Registration',
            'file_path' => $filePath,
        ]);

        DocumentAuditLog::create([
            'physical_document_id' => $doc->id,
            'user_id' => auth()->id(),
            'action_taken' => 'Initial Registration',
            'previous_location' => null,
            'new_location' => 'Office',
        ]);

        $this->reset(['document_type', 'custom_document_type', 'proof_file', 'tki_id', 'showAddModal']);
        session()->flash('success', 'Document added successfully.');
    }

    public function openTransferModal($documentId)
    {
        $this->transferDocumentId = $documentId;
        $this->showTransferModal = true;
    }

    public function submitTransfer()
    {
        $this->validate([
            'newLocation' => 'required',
        ]);

        $this->updateLocation($this->transferDocumentId, $this->newLocation, 'Transferred');
        $this->reset(['showTransferModal', 'transferDocumentId', 'newLocation']);
        session()->flash('success', 'Document transferred successfully.');
    }

    public function updateLocation($documentId, $newLocation, $actionName)
    {
        if (!auth()->check() || (!auth()->user()->hasRole('Super Admin') && !auth()->user()->hasRole('Operational Admin'))) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        $document = PhysicalDocument::findOrFail($documentId);
        $oldLocation = $document->current_location;

        $document->update([
            'current_location' => $newLocation,
            'status' => $actionName
        ]);

        DocumentAuditLog::create([
            'physical_document_id' => $document->id,
            'user_id' => auth()->id(),
            'action_taken' => $actionName,
            'previous_location' => $oldLocation,
            'new_location' => $newLocation,
        ]);
    }

    public function render()
    {
        $query = Tki::with(['physicalDocuments', 'physicalDocuments.auditLogs' => function($q) {
            $q->latest()->with('user');
        }]);

        if ($this->search) {
            $query->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('passport_number', 'like', '%' . $this->search . '%');
        }

        $dbField = match($this->sortBy) {
            'nama' => 'full_name',
            'tanggal_lahir' => 'date_of_birth',
            'tempat_lahir' => 'place_of_birth',
            'negara_tujuan' => 'destination_country',
            'nama_sponsor' => 'sponsor_id',
            default => 'tanggal_daftar'
        };

        return view('livewire.document-tracker', [
            'tkis' => $query->orderBy($dbField, $this->sortDirection)->paginate($this->perPage)
        ])->layout('components.layouts.app');
    }
}

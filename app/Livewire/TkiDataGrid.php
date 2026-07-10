<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Tki;
use App\Exports\TkiExport;
use App\Imports\TkiImport;
use Maatwebsite\Excel\Facades\Excel;

class TkiDataGrid extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $sortBy = 'tanggal_daftar';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $importFile;

    protected $queryString = ['search', 'sortBy', 'sortDirection', 'perPage'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

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

    public function exportXlsx()
    {
        return Excel::download(new TkiExport($this->getQuery()), 'tkis.xlsx');
    }

    public function importData()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        Excel::import(new TkiImport, $this->importFile->path());

        session()->flash('message', 'Data imported successfully.');
        $this->reset('importFile');
    }

    protected function getQuery()
    {
        $query = Tki::query()->with('sponsor');

        if (auth()->check() && auth()->user()->hasRole('Sponsor')) {
            $query->where('sponsor_id', auth()->id());
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('passport_number', 'like', '%' . $this->search . '%')
                  ->orWhere('destination_country', 'like', '%' . $this->search . '%');
            });
        }

        $dbField = match($this->sortBy) {
            'nama' => 'full_name',
            'tanggal_lahir' => 'date_of_birth',
            'tempat_lahir' => 'place_of_birth',
            'negara_tujuan' => 'destination_country',
            'nama_sponsor' => 'sponsor_id',
            default => 'tanggal_daftar'
        };

        return $query->orderBy($dbField, $this->sortDirection);
    }

    public $editData = [];
    public $showEditModal = false;

    public $selectedRows = [];
    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Select all currently paginated TKIs
            $this->selectedRows = collect($this->getQuery()->paginate($this->perPage)->items())
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedRows = [];
        }
    }

    public function deleteSelected()
    {
        if (auth()->check() && auth()->user()->hasRole('Super Admin')) {
            $tkis = Tki::whereIn('id', $this->selectedRows)->get();
            $count = $tkis->count();
            foreach ($tkis as $tki) {
                $tki->delete();
            }
            $this->selectedRows = [];
            $this->selectAll = false;
            session()->flash('message', "{$count} TKI(s) deleted successfully.");
            // Also reset page if needed, but flash message is fine
        }
    }

    public function bulkVerify($status)
    {
        if (auth()->check() && (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Operational Admin'))) {
            $tkis = Tki::whereIn('id', $this->selectedRows)->get();
            foreach ($tkis as $tki) {
                $tki->update(['verification_status' => $status]);
                activity()
                    ->performedOn($tki)
                    ->causedBy(auth()->user())
                    ->log("Verification status updated to {$status} via Bulk Action");
            }
            $this->selectedRows = [];
            $this->selectAll = false;
            session()->flash('message', count($tkis) . " TKI(s) verified as {$status}.");
        }
    }

    public function verifyTki($id, $status)
    {
        if (auth()->check() && (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Operational Admin'))) {
            $tki = Tki::findOrFail($id);
            $tki->update(['verification_status' => $status]);
            
            activity()
                ->performedOn($tki)
                ->causedBy(auth()->user())
                ->log("Verification status updated to {$status}");
                
            session()->flash('message', "TKI verified as {$status}.");
        }
    }

    public function editTki($id)
    {
        $tki = Tki::findOrFail($id);
        $this->editData = collect($tki->toArray())->only([
            'id', 'full_name', 'passport_number', 'gender', 'place_of_birth', 'date_of_birth', 
            'address', 'marital_status', 'mother_name', 'spouse_name', 'education', 
            'destination_country', 'experience_type', 'height', 'weight', 'sponsor_id', 
            'medical_date', 'employer_name', 'visa_status', 'departure_date', 'notes'
        ])->toArray();
        $this->showEditModal = true;
    }

    public function updateTki()
    {
        if (auth()->check() && (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Operational Admin'))) {
            $tki = Tki::findOrFail($this->editData['id']);
            $tki->update($this->editData);
            
            activity()
                ->performedOn($tki)
                ->causedBy(auth()->user())
                ->log("TKI data updated manually.");
                
            $this->showEditModal = false;
            session()->flash('message', 'TKI data updated successfully.');
        }
    }

    public function deleteTki($id)
    {
        if (auth()->check() && auth()->user()->hasRole('Super Admin')) {
            Tki::findOrFail($id)->delete();
            session()->flash('message', 'TKI data soft-deleted successfully.');
        } else {
            session()->flash('error', 'Unauthorized action.');
        }
    }

    public function render()
    {
        return view('livewire.tki-data-grid', [
            'tkis' => $this->getQuery()->paginate($this->perPage),
        ])->layout('components.layouts.app');
    }
}

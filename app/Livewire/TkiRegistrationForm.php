<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tki;
use App\Models\PhysicalDocument;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class TkiRegistrationForm extends Component
{
    use WithFileUploads;

    public $documentScan;
    
    // Form fields
    public $tanggal_daftar;
    public $full_name;
    public $passport_number;
    public $gender = 'L';
    public $place_of_birth;
    public $date_of_birth;
    public $address;
    public $marital_status = 'Single';
    public $mother_name;
    public $spouse_name;
    public $education = 'SMA';
    public $destination_country;
    public $experience_type = 'NON';
    public $height;
    public $weight;
    public $sponsor_id;
    public $medical_date;
    public $employer_name;
    public $visa_status;
    public $departure_date;
    public $notes;
    public $existingTkiId = null;

    public function mount()
    {
        $this->tanggal_daftar = now()->format('Y-m-d');
    }

    protected $rules = [
        'tanggal_daftar' => 'required|date',
        'full_name' => 'required|string|max:255',
        'passport_number' => 'nullable|string|max:255',
        'gender' => 'required|in:L,P',
        'place_of_birth' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
        'address' => 'nullable|string',
        'marital_status' => 'nullable|in:Single,Janda/Duda,Menikah',
        'mother_name' => 'nullable|string|max:255',
        'spouse_name' => 'nullable|string|max:255',
        'education' => 'nullable|in:SD,SMP,SMA,Diploma,Strata',
        'destination_country' => 'nullable|string|max:255',
        'experience_type' => 'nullable|in:NON,EX',
        'height' => 'nullable|integer|min:50|max:250',
        'weight' => 'nullable|integer|min:20|max:200',
        'sponsor_id' => 'required|exists:users,id',
        'medical_date' => 'nullable|date',
        'employer_name' => 'nullable|string|max:255',
        'visa_status' => 'nullable|string|max:255',
        'departure_date' => 'nullable|date',
        'notes' => 'nullable|string',

    ];

    public function updatedDocumentScan()
    {
        $this->validate([
            'documentScan' => 'image|max:5120', // 5MB Max
        ]);

        try {
            // GCP Vision API integration
            $extractedData = $this->extractTextFromImage($this->documentScan->getRealPath());

            // Auto-fill form fields
            $this->full_name = $extractedData['full_name'] ?? $this->full_name;
            $this->passport_number = $extractedData['passport_number'] ?? $this->passport_number;
            $this->place_of_birth = $extractedData['place_of_birth'] ?? $this->place_of_birth;
            $this->gender = $extractedData['gender'] ?? $this->gender;
            $this->date_of_birth = $extractedData['date_of_birth'] ?? $this->date_of_birth;
            
            $this->dispatch('notify', message: 'Data OCR berhasil diekstrak!', type: 'success');
        } catch (\Exception $e) {
            Log::error('OCR Exception: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Gagal membaca dokumen: ' . $e->getMessage(), type: 'danger');
        }
    }

    private function extractTextFromImage($imagePath)
    {
        $imageAnnotator = new ImageAnnotatorClient();
        $image = file_get_contents($imagePath);
        $response = $imageAnnotator->documentTextDetection($image);
        $annotation = $response->getFullTextAnnotation();
        
        $text = $annotation ? $annotation->getText() : '';
        $imageAnnotator->close();

        if (!$text) {
            throw new \Exception("Tidak ada teks terdeteksi.");
        }

        $data = [];

        // Passport Number: Regex looking for 1-2 letters followed by 7 digits
        if (preg_match('/(?:[A-Z]{1,2})\s*(\d{7})/i', $text, $matches)) {
            $data['passport_number'] = strtoupper(str_replace(' ', '', $matches[0]));
        }

        // Gender: Regex looking for L/P or LAKI-LAKI/PEREMPUAN
        if (preg_match('/(LAKI-LAKI|PEREMPUAN)\b/i', $text, $matches)) {
            $data['gender'] = strtoupper($matches[1]) === 'LAKI-LAKI' ? 'L' : 'P';
        } elseif (preg_match('/\b(L|P)\b/i', $text, $matches)) {
            $data['gender'] = strtoupper($matches[1]);
        }

        // Date of Birth: Regex extracting standard date formats (DD-MM-YYYY or DD MMM YYYY)
        if (preg_match('/\b(\d{2})[\s\-\/]([a-zA-Z]{3}|\d{2})[\s\-\/](\d{4})\b/', $text, $matches)) {
            try {
                $dateStr = $matches[0];
                $data['date_of_birth'] = \Carbon\Carbon::parse($dateStr)->format('Y-m-d');
            } catch (\Exception $e) {
                // Ignore parsing failure
            }
        }

        // Extremely naive fallback for name and place of birth if needed
        // Since parsing Name and Place of Birth requires complex NLP, we'll try to find lines above Date of Birth
        // But for strict constraints, we'll map what we have reliably.
        
        return $data;
    }

    public function submit()
    {
        $this->validate();

        // Duplicate Check
        if (!$this->existingTkiId) {
            $duplicate = Tki::where('full_name', $this->full_name)
                            ->where('date_of_birth', $this->date_of_birth)
                            ->first();

            if ($duplicate) {
                $this->existingTkiId = $duplicate->id;
                $this->dispatch('confirm-overwrite');
                return;
            }
        }

        $data = [
            'tanggal_daftar' => $this->tanggal_daftar,
            'full_name' => $this->full_name,
            'passport_number' => $this->passport_number,
            'gender' => $this->gender,
            'place_of_birth' => $this->place_of_birth,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->address,
            'marital_status' => $this->marital_status,
            'mother_name' => $this->mother_name,
            'spouse_name' => $this->spouse_name,
            'education' => $this->education,
            'destination_country' => $this->destination_country,
            'experience_type' => $this->experience_type,
            'height' => $this->height,
            'weight' => $this->weight,
            'sponsor_id' => $this->sponsor_id,
            'medical_date' => $this->medical_date,
            'employer_name' => $this->employer_name,
            'visa_status' => $this->visa_status,
            'departure_date' => $this->departure_date,
            'notes' => $this->notes,
            'registration_date' => now(),
            'verification_status' => 'Pending',
        ];

        if ($this->existingTkiId) {
            $tki = Tki::findOrFail($this->existingTkiId);
            $tki->update($data);
            $message = 'TKI data overwritten successfully.';
        } else {
            $tki = Tki::create($data);
            $message = 'TKI registered successfully.';
        }


        if ($this->documentScan) {
            PhysicalDocument::create([
                'tki_id' => $tki->id,
                'document_type' => 'passport',
                'current_location' => 'Office',
                'status' => 'Scanned and Stored',
            ]);
        }

        session()->flash('success', $message);
        $this->reset([
            'existingTkiId', 'tanggal_daftar', 'full_name', 'passport_number', 'gender', 'place_of_birth', 'date_of_birth', 
            'address', 'marital_status', 'mother_name', 'spouse_name', 'education', 
            'destination_country', 'experience_type', 'height', 'weight', 'sponsor_id', 
            'medical_date', 'employer_name', 'visa_status', 'departure_date', 'notes', 
            'documentScan'
        ]);
    }

    public function render()
    {
        // Fetch sponsors for the dropdown
        $sponsors = \App\Models\User::role('Sponsor')->get();
        return view('livewire.tki-registration-form', ['sponsors' => $sponsors])->layout('components.layouts.app');
    }
}

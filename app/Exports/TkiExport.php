<?php

namespace App\Exports;

use App\Models\Tki;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TkiExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Sponsor ID',
            'Registration Date',
            'Full Name',
            'Gender',
            'Place of Birth',
            'Date of Birth',
            'Address',
            'Marital Status',
            'Mother Name',
            'Spouse Name',
            'Education',
            'Destination Country',
            'Experience Type',
            'Height Weight',
            'Passport Number',
            'Medical Date',
            'Employer Name',
            'Visa Status',
            'Departure Date',
            'Notes',
            'Created At',
            'Updated At',
        ];
    }
}

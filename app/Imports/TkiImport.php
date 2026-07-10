<?php

namespace App\Imports;

use App\Models\Tki;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class TkiImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        // 1. Skip empty rows based on TKI Name
        if (empty($row['nama_tki'])) {
            return null;
        }

        // 2. Intelligent Height/Weight (TB / BB) Splitter
        $height = null;
        $weight = null;
        if (!empty($row['tb_bb'])) {
            $parts = explode('/', $row['tb_bb']);
            $height = isset($parts[0]) ? (int) preg_replace('/[^0-9]/', '', $parts[0]) : null;
            $weight = isset($parts[1]) ? (int) preg_replace('/[^0-9]/', '', $parts[1]) : null;
        }

        // 3. Status Translation
        $statusRaw = strtoupper(trim($row['status'] ?? ''));
        $maritalStatus = 'Single'; // default
        if (in_array($statusRaw, ['KAWIN', 'MENIKAH'])) $maritalStatus = 'Menikah';
        if (in_array($statusRaw, ['CERAI', 'JANDA', 'DUDA'])) $maritalStatus = 'Janda/Duda';

        // 4. Sponsor Lookup (Soft Match)
        $sponsorId = null;
        if (!empty($row['sponsor'])) {
            $sponsor = User::role('Sponsor')->where('name', 'LIKE', '%' . trim($row['sponsor']) . '%')->first();
            $sponsorId = $sponsor ? $sponsor->id : null;
        }

        // 5. Experience Type strict mapping (MySQL enum 'NON', 'EX')
        $expRaw = strtoupper(trim($row['non_ex'] ?? 'NON'));
        $experienceType = 'NON';
        // If it explicitly says NON, it's NON. 
        // If it's anything else (like 'EX', 'MALAYSIA 1', 'SAUDI', etc), we assume it's EX.
        if ($expRaw !== 'NON' && $expRaw !== '') {
            if (str_contains($expRaw, 'NON')) {
                $experienceType = 'NON';
            } else {
                $experienceType = 'EX';
            }
        }

        return new Tki([
            'tanggal_daftar'      => $this->parseDate($row['tanggal_daftar'] ?? $row['tgl_daftar'] ?? null),
            'registration_date'   => $this->parseDate($row['tgl_daftar'] ?? null),
            'full_name'           => $row['nama_tki'],
            'gender'              => strtoupper(trim($row['lp'] ?? 'P')),
            'place_of_birth'      => $row['tempat_lahir'] ?? null,
            'date_of_birth'       => $this->parseDate($row['tgl_lahir'] ?? null),
            'address'             => $row['alamat'] ?? null,
            'marital_status'      => $maritalStatus,
            'mother_name'         => $row['nama_ibu'] ?? null,
            'spouse_name'         => $row['nama_pasangan'] ?? null,
            'education'           => strtoupper(trim($row['pend'] ?? 'SD')),
            'destination_country' => $row['tujuan'] ?? null,
            'experience_type'     => $experienceType,
            'height'              => $height,
            'weight'              => $weight,
            'sponsor_id'          => $sponsorId,
            'passport_number'     => $row['no_paspor'] ?? null,
            'medical_date'        => $this->parseDate($row['tgl_medical'] ?? null),
            'employer_name'       => $row['nama_majikan'] ?? null,
            'visa_status'         => $row['visa'] ?? null,
            'verification_status' => 'Pending', // Force pending state for audit
        ]);
    }

    private function parseDate($value)
    {
        if (empty($value)) return null;
        
        try {
            // Handle Excel serial numeric date
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            // Handle human string date (DD/MM/YYYY)
            return Carbon::createFromFormat('d/m/Y', trim($value))->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Fail silently, leave date blank for manual fix
        }
    }

    public function batchSize(): int
    {
        return 100;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Tki;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CvExportController extends Controller
{
    public function download(Tki $tki)
    {
        $pdf = Pdf::loadView('pdf.tki-cv', ['tki' => $tki])->setPaper('a4', 'portrait');
        return $pdf->stream('CV_' . str_replace(' ', '_', $tki->full_name) . '.pdf');
    }
}

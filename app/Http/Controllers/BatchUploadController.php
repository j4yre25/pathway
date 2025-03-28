<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

class BatchUploadController extends Controller
{
    public function downloadTemplate()
{
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="graduate_template.csv"',
    ];

    $csvContent = "Name,Program,Year-Graduated,Current-Job-Title\n";

    return Response::make($csvContent, 200, $headers);
}
}

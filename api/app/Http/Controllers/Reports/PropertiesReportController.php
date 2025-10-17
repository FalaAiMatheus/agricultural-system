<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Property;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PropertiesReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $properties = Property::all()->toArray();

        $sheet->setCellValue('A1', 'Nome');
        $sheet->setCellValue('B1', 'Município');
        $sheet->setCellValue('C1', 'UF');
        $sheet->setCellValue('D1', 'Inscrição Estadual');
        $sheet->setCellValue('E1', 'Área Total');

        $row = 2;
        foreach ($properties as $property) {
            $sheet->setCellValue('A' . $row, $property['name']);
            $sheet->setCellValue('B' . $row, $property['municipality']);
            $sheet->setCellValue('C' . $row, $property['uf']);
            $sheet->setCellValue('D' . $row, $property['state_registration']);
            $sheet->setCellValue('E' . $row, $property['total_area']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="properties.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}

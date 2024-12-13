<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExporter
{
    public static function exportToExcel($headings, $rows, $filename = 'export')
    {


         // Append .xlsx extension if not provided
         if (!preg_match('/\.xlsx$/', $filename)) {
            $filename .= '.xlsx';
        }

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Set the headings
        $sheet->fromArray([$headings]);

        // Add the rows
        $sheet->fromArray($rows, null, 'A2');

        // Create a new Excel Writer
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Save the Excel file to output stream
        $writer->save('php://output');
    }
}
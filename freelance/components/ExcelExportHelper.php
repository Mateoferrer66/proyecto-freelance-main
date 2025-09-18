<?php

namespace app\components;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate; // Agregado para conversión de columnas
use Yii;


class ExcelExportHelper
{
    /** 
     * Exporta datos a Excel.
     *
     * @param string $filename Nombre del archivo sin extensión
     * @param array $headers Encabezados de columna
     * @param array $data Datos en formato array de arrays
     * @return \yii\web\Response
     */ 
    public static function export($filename, array $headers, array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        foreach ($headers as $col => $header) {
            $columnLetter = Coordinate::stringFromColumnIndex($col + 1);
            $sheet->setCellValue($columnLetter . '1', $header);
        }

        // Datos
        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue($columnLetter . ($rowIndex + 2), $value);
            }
        }

        // Guardar en archivo temporal
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);

        // Enviar al navegador
        return Yii::$app->response->sendFile($tempFile, $filename . '.xlsx', [
            'mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'inline' => false,
        ]);
    }
    // Exportar a Excel
   
}
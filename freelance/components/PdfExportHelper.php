<?php

namespace app\components;

use Mpdf\Mpdf;
use Yii;

class PdfExportHelper
{
    /**
     * Exporta contenido HTML como PDF.
     *
     * @param string $filename Nombre del archivo sin extensión
     * @param string $html Contenido HTML a renderizar
     * @param array $options Opciones adicionales (título, orientación, etc.)
     * @return \yii\web\Response
     */
    public static function export($filename, $html, $options = [])
    {
        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => $options['orientation'] ?? 'P',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetTitle($options['title'] ?? $filename);
        $mpdf->WriteHTML($html);

        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
        $mpdf->Output($tempFile, \Mpdf\Output\Destination::FILE);

        return Yii::$app->response->sendFile($tempFile, $filename . '.pdf', [
            'mimeType' => 'application/pdf',
            'inline' => false,
        ]);
    }
}
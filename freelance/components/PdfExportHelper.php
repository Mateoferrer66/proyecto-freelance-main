<?php
namespace app\components;

use Mpdf\Mpdf;
use Yii;

class PdfExportHelper
{

    /**
     * Exporta contenido HTML como PDF, solo guarda el PDF
     *
     * @param string $filename Nombre del archivo sin extensión
     * @param string $html Contenido HTML a renderizar
     * @param array $options Opciones adicionales:
     *   - orientation: 'P' o 'L' (portrait/landscape)
     *   - title: título del documento
     *   - header: string HTML del encabezado, o array ['odd' => '...', 'even' => '...']
     *   - footer: string HTML del pie de página, o array ['odd' => '...', 'even' => '...']
     *   - margins: array ['top' => 10, 'bottom' => 10, 'left' => 15, 'right' => 15, 'header' => 5, 'footer' => 5]
     *   - mirrorMargins: bool para márgenes espejo (izquierda/derecha alternados)
     * @return string
     */
    public static function save($filename, $html, $options = []): string
    {
        $margins = array_merge([
            'top'    => 10,
            'bottom' => 10,
            'left'   => 15,
            'right'  => 15,
            'header' => 5,
            'footer' => 5,
        ], $options['margins'] ?? []);

        $mpdf = new Mpdf([
            'format'          => $options['format'] ?? 'A4',
            'orientation'     => $options['orientation'] ?? 'P',
            'margin_top'      => $margins['top'],
            'margin_bottom'   => $margins['bottom'],
            'margin_left'     => $margins['left'],
            'margin_right'    => $margins['right'],
            'margin_header'   => $margins['header'],
            'margin_footer'   => $margins['footer'],
            'mirrorMargins'   => $options['mirrorMargins'] ?? 0,
        ]);

        $mpdf->SetTitle($options['title'] ?? $filename);

        // ── Header ──────────────────────────────────────────────────────────
        if (!empty($options['header'])) {
            $header = $options['header'];

            if (is_array($header)) {
                // Header distinto para páginas pares e impares
                $mpdf->SetHTMLHeader($header['odd']  ?? '', 'O');
                $mpdf->SetHTMLHeader($header['even'] ?? '', 'E');
            } else {
                // Mismo header para todas las páginas
                $mpdf->SetHTMLHeader($header);
            }
        }

        // ── Footer ──────────────────────────────────────────────────────────
        if (!empty($options['footer'])) {
            $footer = $options['footer'];

            if (is_array($footer)) {
                $mpdf->SetHTMLFooter($footer['odd']  ?? '', 'O');
                $mpdf->SetHTMLFooter($footer['even'] ?? '', 'E');
            } else {
                $mpdf->SetHTMLFooter($footer);
            }
        }

        $mpdf->WriteHTML($html);

        // Verifica cuántas páginas generó
        Yii::error('Páginas generadas: ' . $mpdf->page, 'pdf');

        // ── Ruta de guardado ────────────────────────────────────────────────
        if (!empty($options['savePath'])) {
            $outputPath = Yii::getAlias($options['savePath']);
        } else {
            $outputPath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
        }

        // Crear directorio si no existe
        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $mpdf->Output($outputPath, \Mpdf\Output\Destination::FILE);

        // Verifica que el archivo existe y tiene peso
        Yii::error('Archivo generado: ' . $outputPath, 'pdf');
        Yii::error('Tamaño del archivo: ' . filesize($outputPath) . ' bytes', 'pdf');

        return $outputPath; // retorna la ruta, no envía al navegador
    }

    /**
     * Exporta contenido HTML como PDF.
     *
     * @param string $filename Nombre del archivo sin extensión
     * @param string $html Contenido HTML a renderizar
     * @param array $options Opciones adicionales:
     *   - orientation: 'P' o 'L' (portrait/landscape)
     *   - title: título del documento
     *   - header: string HTML del encabezado, o array ['odd' => '...', 'even' => '...']
     *   - footer: string HTML del pie de página, o array ['odd' => '...', 'even' => '...']
     *   - margins: array ['top' => 10, 'bottom' => 10, 'left' => 15, 'right' => 15, 'header' => 5, 'footer' => 5]
     *   - mirrorMargins: bool para márgenes espejo (izquierda/derecha alternados)
     * @return \yii\web\Response
     */
    public static function export($filename, $html, $options = []): \yii\web\Response
    {
        $path = self::save($filename, $html, $options);
        return Yii::$app->response->sendFile($path, $filename . '.pdf', [
            'mimeType' => 'application/pdf',
            'inline'   => $options['inline'] ?? false,
        ]);
    }
}
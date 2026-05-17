<?php
namespace app\services;

use app\components\PdfExportHelper;
use app\components\UtilitiesHelper;
use app\models\Socio;
use Yii;

class SocioService
{
    public static function generateContract(Socio $socio): string
    {
        // Renderizar la vista del contrato como HTML
        $html = Yii::$app->controller->renderPartial(
            '@app/views/socio/_contract',
            ['model' => $socio]
        );

        $html = mb_convert_encoding($html, 'UTF-8', 'auto');
        
        $savePath = "@app/web/uploads/members/contract/ContratoFreelanceSocio{$socio->soc_numero}.pdf";

        PdfExportHelper::save(
            "ContratoFreelanceSocio{$socio->soc_numero}",
            $html,
            [
                'title'    => "Contrato Socio #{$socio->soc_numero}",
                'savePath' => $savePath,
                'footer'   => '<table width="100%">
                                    <tr>
                                        <td style="width: 800; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #848484;"><strong>Contrato de Adhesión - Página [[page_cu]] de [[page_nb]] -</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 800; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #848484;"><strong>Freelance Sociedad Cooperativa Madrileña</strong>Av Monasterio de Silos 13 Local, 28049 Madrid España </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 800; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #848484;">Tel y Fax (34) 91 383 96 52 info@freelance.es www.freelance.es</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 800; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #848484;">NIF / VAT (ES) F84278266 <span style="font-size: 6px;">Inscrita en el registro de cooperativas de la Comunidad de Madrid, Tomo 34, Folio 4269, Inscripción 28/CM-4269</span></td>
                                    </tr>
                               </table>',
                'margins'  => ['top' => 12, 'bottom' => 20, 'left' => 20, 'right' => 15],
            ]
        );

        return Yii::getAlias($savePath);
    }
}
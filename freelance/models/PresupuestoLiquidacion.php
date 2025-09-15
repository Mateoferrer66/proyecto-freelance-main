<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presupuesto_liquidacion".
 *
 * @property int $liq_id Código identificador del registro
 * @property int $usu_id Usuario que realiza la liquidación
 * @property int $soc_id Socio al cual se le realiza la liquidación
 * @property string $liq_numero Número consecutivo de la liquidación
 * @property string $liq_fecha Fecha de la liquidación
 * @property string $liq_a_favor
 * @property float $liq_irpf Porcentaje de retencion IRPF
 * @property float $liq_irpf_valor Valor IRPF
 * @property float $liq_ret_imp_soc Porcentaje de retención a cuenta impuesto de sociedades
 * @property float $liq_ret_imp_soc_valor Valor Impuesto sociedades
 * @property float $liq_total_neto Total facturado por el socio
 * @property float $liq_total_gastos Suma total de gastos
 * @property float $liq_total_retenciones Suma total de retenciones
 * @property float|null $liq_iva_facturas Suma total de IVA de las facturas, en caso de que sea una liquidación a favor de la empresa
 * @property float $liq_ingreso_liquido Total percibido por el socio o por la empresa
 * @property string|null $liq_observaciones
 * @property int $liq_exportada Campo para indicar si la liquidacion ya fue exportada
 * @property int $liq_transferencia Campo para indicar si la liquidacion ya fue exportada en excel de transferencias
 * @property int $liq_eliminada Campo para indicar si la liquidación se encuentra eliminada: 1 - Si, 0 - No
 */
class PresupuestoLiquidacion extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const LIQ_A_FAVOR_SOCIO = 'socio';
    const LIQ_A_FAVOR_EMPRESA = 'empresa';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presupuesto_liquidacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['liq_iva_facturas', 'liq_observaciones'], 'default', 'value' => null],
            [['liq_a_favor'], 'default', 'value' => 'socio'],
            [['liq_eliminada'], 'default', 'value' => 0],
            [['usu_id', 'soc_id', 'liq_numero', 'liq_fecha', 'liq_irpf', 'liq_ret_imp_soc', 'liq_total_neto', 'liq_total_gastos', 'liq_total_retenciones', 'liq_ingreso_liquido'], 'required'],
            [['usu_id', 'soc_id', 'liq_exportada', 'liq_transferencia', 'liq_eliminada'], 'integer'],
            [['liq_fecha'], 'safe'],
            [['liq_a_favor', 'liq_observaciones'], 'string'],
            [['liq_irpf', 'liq_irpf_valor', 'liq_ret_imp_soc', 'liq_ret_imp_soc_valor', 'liq_total_neto', 'liq_total_gastos', 'liq_total_retenciones', 'liq_iva_facturas', 'liq_ingreso_liquido'], 'number'],
            [['liq_numero'], 'string', 'max' => 45],
            ['liq_a_favor', 'in', 'range' => array_keys(self::optsLiqAFavor())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'liq_id' => 'Liq ID',
            'usu_id' => 'Usu ID',
            'soc_id' => 'Soc ID',
            'liq_numero' => 'Liq Numero',
            'liq_fecha' => 'Liq Fecha',
            'liq_a_favor' => 'Liq A Favor',
            'liq_irpf' => 'Liq Irpf',
            'liq_irpf_valor' => 'Liq Irpf Valor',
            'liq_ret_imp_soc' => 'Liq Ret Imp Soc',
            'liq_ret_imp_soc_valor' => 'Liq Ret Imp Soc Valor',
            'liq_total_neto' => 'Liq Total Neto',
            'liq_total_gastos' => 'Liq Total Gastos',
            'liq_total_retenciones' => 'Liq Total Retenciones',
            'liq_iva_facturas' => 'Liq Iva Facturas',
            'liq_ingreso_liquido' => 'Liq Ingreso Liquido',
            'liq_observaciones' => 'Liq Observaciones',
            'liq_exportada' => 'Liq Exportada',
            'liq_transferencia' => 'Liq Transferencia',
            'liq_eliminada' => 'Liq Eliminada',
        ];
    }


    /**
     * column liq_a_favor ENUM value labels
     * @return string[]
     */
    public static function optsLiqAFavor()
    {
        return [
            self::LIQ_A_FAVOR_SOCIO => 'socio',
            self::LIQ_A_FAVOR_EMPRESA => 'empresa',
        ];
    }

    /**
     * @return string
     */
    public function displayLiqAFavor()
    {
        return self::optsLiqAFavor()[$this->liq_a_favor];
    }

    /**
     * @return bool
     */
    public function isLiqAFavorSocio()
    {
        return $this->liq_a_favor === self::LIQ_A_FAVOR_SOCIO;
    }

    public function setLiqAFavorToSocio()
    {
        $this->liq_a_favor = self::LIQ_A_FAVOR_SOCIO;
    }

    /**
     * @return bool
     */
    public function isLiqAFavorEmpresa()
    {
        return $this->liq_a_favor === self::LIQ_A_FAVOR_EMPRESA;
    }

    public function setLiqAFavorToEmpresa()
    {
        $this->liq_a_favor = self::LIQ_A_FAVOR_EMPRESA;
    }
}

<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Factura;

/**
 * FacturaSearch represents the model behind the search form of `app\models\Factura`.
 */
class FacturaSearch extends Factura
{
    public $cli_nombre;
    public $cli_nif;
    public $soc_numero;
    public $soc_nombre;
    public $fecha_inicio;
    public $fecha_fin;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fac_id', 'fac_numero', 'cli_id', 'soc_id', 'fdp_id', 'fac_exportada', 'fac_eliminada'], 'integer'],
            [['fac_logo', 'fac_fecha', 'fac_language', 'fac_money', 'fac_estado', 'fac_situacion', 'fac_fecha_situacion', 'fac_observaciones', 'cli_nombre', 'cli_nif', 'soc_numero', 'soc_nombre', 'fecha_inicio', 'fecha_fin'], 'safe'],
            [['fac_subtotal', 'fac_iva', 'fac_gastos_suplidos', 'fac_total'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Factura::find()
            ->joinWith(['cli', 'soc'])
            ->where(['factura.fac_eliminada' => 0]); // ← siempre excluir eliminadas

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['fac_fecha' => SORT_DESC, 'fac_id' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['cli_nombre'] = [
            'asc' => ['cliente.cli_nombre' => SORT_ASC],
            'desc' => ['cliente.cli_nombre' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['soc_nombre'] = [
            'asc' => ['socio.soc_nombre' => SORT_ASC],
            'desc' => ['socio.soc_nombre' => SORT_DESC],
        ];


        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'factura.fac_id' => $this->fac_id,
            'factura.fac_numero' => $this->fac_numero,
            // 'factura.fac_fecha' => $this->fac_fecha, // Handled by date range
            'factura.cli_id' => $this->cli_id,
            'factura.soc_id' => $this->soc_id,
            'factura.fdp_id' => $this->fdp_id,
            'factura.fac_fecha_situacion' => $this->fac_fecha_situacion,
            'factura.fac_subtotal' => $this->fac_subtotal,
            'factura.fac_iva' => $this->fac_iva,
            'factura.fac_gastos_suplidos' => $this->fac_gastos_suplidos,
            'factura.fac_total' => $this->fac_total,
            'factura.fac_exportada' => $this->fac_exportada,
            'factura.fac_eliminada' => $this->fac_eliminada,
        ]);

        $query->andFilterWhere(['like', 'factura.fac_logo', $this->fac_logo])
            ->andFilterWhere(['like', 'factura.fac_language', $this->fac_language])
            ->andFilterWhere(['like', 'factura.fac_money', $this->fac_money])
            ->andFilterWhere(['like', 'factura.fac_estado', $this->fac_estado])
            ->andFilterWhere(['like', 'factura.fac_situacion', $this->fac_situacion])
            ->andFilterWhere(['like', 'factura.fac_observaciones', $this->fac_observaciones])
            ->andFilterWhere(['like', 'cliente.cli_nombre', $this->cli_nombre])
            ->andFilterWhere(['like', 'cliente.cli_nif', $this->cli_nif])
            ->andFilterWhere(['like', 'socio.soc_numero', $this->soc_numero])
            ->andFilterWhere(['like', 'socio.soc_nombre', $this->soc_nombre]);

        if (!empty($this->fecha_inicio)) {
            $query->andFilterWhere(['>=', 'factura.fac_fecha', $this->fecha_inicio]);
        }

        if (!empty($this->fecha_fin)) {
            $query->andFilterWhere(['<=', 'factura.fac_fecha', $this->fecha_fin]);
        }

        return $dataProvider;
    }
}

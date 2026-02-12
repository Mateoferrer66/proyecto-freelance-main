<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Presupuesto;

/**
 * PresupuestoSearch represents the model behind the search form of `app\models\Presupuesto`.
 */
class PresupuestoSearch extends Model
{
    public $pre_id;
    public $pre_numero;
    public $pre_logo;
    public $pre_fecha;
    public $pre_language;
    public $cli_id;
    public $soc_id;
    public $fdp_id;
    public $pre_subtotal;
    public $pre_iva;
    public $pre_gastos_suplidos;
    public $pre_total;
    public $pre_observaciones;
    public $pre_eliminado;
    public $pre_estado;
    public $pre_situacion;
    public $pre_fecha_situacion;
    public $cli_nif;
    public $cli_nombre;
    public $soc_codigo;
    public $soc_nombre;
    public $fecha_inicio;
    public $fecha_fin;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pre_id', 'cli_id', 'soc_id', 'fdp_id', 'pre_eliminado'], 'integer'],
            [['pre_numero', 'pre_logo', 'pre_fecha', 'pre_language', 'pre_observaciones', 'cli_nif', 'cli_nombre', 'soc_codigo', 'soc_nombre', 'fecha_inicio', 'fecha_fin'], 'safe'],
            [['pre_subtotal', 'pre_iva', 'pre_gastos_suplidos', 'pre_total'], 'number'],
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
        $query = Presupuesto::find()->joinWith(['cli', 'soc']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['pre_fecha' => SORT_DESC, 'pre_id' => SORT_DESC]],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pre_id' => $this->pre_id,
            'pre_fecha' => $this->pre_fecha,
            'cli_id' => $this->cli_id,
            'soc_id' => $this->soc_id,
            'fdp_id' => $this->fdp_id,
            'pre_subtotal' => $this->pre_subtotal,
            'pre_iva' => $this->pre_iva,
            'pre_gastos_suplidos' => $this->pre_gastos_suplidos,
            'pre_total' => $this->pre_total,
            'pre_eliminado' => $this->pre_eliminado,
        ]);

        $query->andFilterWhere(['like', 'pre_numero', $this->pre_numero])
            ->andFilterWhere(['like', 'pre_logo', $this->pre_logo])
            ->andFilterWhere(['like', 'pre_language', $this->pre_language])
            ->andFilterWhere(['like', 'pre_observaciones', $this->pre_observaciones])
            ->andFilterWhere(['like', 'cliente.cli_numdocide', $this->cli_nif])
            ->andFilterWhere(['like', 'cliente.cli_nombre', $this->cli_nombre])
            ->andFilterWhere(['like', 'socio.soc_numero', $this->soc_codigo])
            ->andFilterWhere(['like', 'socio.soc_nombre', $this->soc_nombre])
            ->andFilterWhere(['>=', 'pre_fecha', $this->fecha_inicio])
            ->andFilterWhere(['<=', 'pre_fecha', $this->fecha_fin]);

        return $dataProvider;
    }
}

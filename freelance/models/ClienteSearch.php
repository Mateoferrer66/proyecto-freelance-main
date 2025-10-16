<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cliente;

/**
 * ClienteSearch represents the model behind the search form of `app\models\Cliente`.
 */
class ClienteSearch extends Cliente
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cli_id', 'cli_numero', 'tdo_id', 'pai_id', 'prv_id', 'iva_id', 'fdp_id', 'soc_id', 'cli_exportado', 'cli_eliminado'], 'integer'],
            [['cli_nombre', 'cli_persona_contacto', 'cli_docinipais', 'cli_numdocide', 'cli_feccaddoc', 'cli_tel1', 'cli_tel2', 'cli_direccion', 'cli_poblacion', 'cli_codpostal', 'cli_email', 'cli_cuenta_contable', 'cli_observaciones', 'cli_estado'], 'safe'],
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
        $query = Cliente::find()->where(['cli_eliminado' => 0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'cli_id' => $this->cli_id,
            'cli_numero' => $this->cli_numero,
            'tdo_id' => $this->tdo_id,
            'cli_feccaddoc' => $this->cli_feccaddoc,
            'pai_id' => $this->pai_id,
            'prv_id' => $this->prv_id,
            'iva_id' => $this->iva_id,
            'fdp_id' => $this->fdp_id,
            'soc_id' => $this->soc_id,
            'cli_exportado' => $this->cli_exportado,
            'cli_eliminado' => $this->cli_eliminado,
        ]);

        $query->andFilterWhere(['like', 'cli_nombre', $this->cli_nombre])
            ->andFilterWhere(['like', 'cli_persona_contacto', $this->cli_persona_contacto])
            ->andFilterWhere(['like', 'cli_docinipais', $this->cli_docinipais])
            ->andFilterWhere(['like', 'cli_numdocide', $this->cli_numdocide])
            ->andFilterWhere(['like', 'cli_tel1', $this->cli_tel1])
            ->andFilterWhere(['like', 'cli_tel2', $this->cli_tel2])
            ->andFilterWhere(['like', 'cli_direccion', $this->cli_direccion])
            ->andFilterWhere(['like', 'cli_poblacion', $this->cli_poblacion])
            ->andFilterWhere(['like', 'cli_codpostal', $this->cli_codpostal])
            ->andFilterWhere(['like', 'cli_email', $this->cli_email])
            ->andFilterWhere(['like', 'cli_cuenta_contable', $this->cli_cuenta_contable])
            ->andFilterWhere(['like', 'cli_observaciones', $this->cli_observaciones])
            ->andFilterWhere(['like', 'cli_estado', $this->cli_estado]);

        return $dataProvider;
    }
}
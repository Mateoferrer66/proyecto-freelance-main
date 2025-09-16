<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConceptoFacturacion;

/**
 * ConceptoFacturacionSearch represents the model behind the search form of `app\models\ConceptoFacturacion`.
 */
class ConceptoFacturacionSearch extends ConceptoFacturacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cof_id', 'iva_id', 'cof_eliminado'], 'integer'],
            [['cof_codigo', 'cof_nombre', 'cof_clasificacion'], 'safe'],
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
        $query = ConceptoFacturacion::find();

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
            'cof_id' => $this->cof_id,
            'iva_id' => $this->iva_id,
            'cof_eliminado' => $this->cof_eliminado,
        ]);

        $query->andFilterWhere(['like', 'cof_codigo', $this->cof_codigo])
            ->andFilterWhere(['like', 'cof_nombre', $this->cof_nombre])
            ->andFilterWhere(['like', 'cof_clasificacion', $this->cof_clasificacion]);

        return $dataProvider;
    }
}

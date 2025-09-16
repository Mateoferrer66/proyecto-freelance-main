<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConceptoLiquidacion;

/**
 * ConceptoLiquidacionSearch represents the model behind the search form of `app\models\ConceptoLiquidacion`.
 */
class ConceptoLiquidacionSearch extends ConceptoLiquidacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['col_id', 'col_eliminado'], 'integer'],
            [['col_nombre', 'col_clasificacion', 'col_tipo'], 'safe'],
            [['col_porcentaje', 'col_valor'], 'number'],
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
        $query = ConceptoLiquidacion::find();

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
            'col_id' => $this->col_id,
            'col_porcentaje' => $this->col_porcentaje,
            'col_valor' => $this->col_valor,
            'col_eliminado' => $this->col_eliminado,
        ]);

        $query->andFilterWhere(['like', 'col_nombre', $this->col_nombre])
            ->andFilterWhere(['like', 'col_clasificacion', $this->col_clasificacion])
            ->andFilterWhere(['like', 'col_tipo', $this->col_tipo]);

        return $dataProvider;
    }
}

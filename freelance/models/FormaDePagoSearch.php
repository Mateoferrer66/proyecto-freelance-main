<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FormaDePago;

/**
 * FormaDePagoSearch represents the model behind the search form of `app\models\FormaDePago`.
 */
class FormaDePagoSearch extends FormaDePago
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fdp_id', 'fdp_eliminada'], 'integer'],
            [['fdp_nombre'], 'safe'],
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
        $query = FormaDePago::find();

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
            'fdp_id' => $this->fdp_id,
            'fdp_eliminada' => $this->fdp_eliminada,
        ]);

        $query->andFilterWhere(['like', 'fdp_nombre', $this->fdp_nombre]);

        return $dataProvider;
    }
}

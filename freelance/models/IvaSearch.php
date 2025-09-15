<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Iva;

/**
 * IvaSearch represents the model behind the search form of `app\models\Iva`.
 */
class IvaSearch extends Iva
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iva_id'], 'integer'],
            [['iva_concepto'], 'safe'],
            [['iva_porcentaje'], 'number'],
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
        $query = Iva::find();

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
            'iva_id' => $this->iva_id,
            'iva_porcentaje' => $this->iva_porcentaje,
        ]);

        $query->andFilterWhere(['like', 'iva_concepto', $this->iva_concepto]);

        return $dataProvider;
    }
}

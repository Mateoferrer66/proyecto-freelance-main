<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Banco;

/**
 * BancoSearch represents the model behind the search form of `app\models\Banco`.
 */
class BancoSearch extends Banco
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ban_id', 'ban_eliminado'], 'integer'],
            [['ban_nombre', 'ban_numcuenta'], 'safe'],
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
        $query = Banco::find();

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
            'ban_id' => $this->ban_id,
            'ban_eliminado' => $this->ban_eliminado,
        ]);

        $query->andFilterWhere(['like', 'ban_nombre', $this->ban_nombre])
            ->andFilterWhere(['like', 'ban_numcuenta', $this->ban_numcuenta]);

        return $dataProvider;
    }
}

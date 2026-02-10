<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Socio;

/**
 * ClienteSearch represents the model behind the search form of `app\models\Cliente`.
 */
class SocioSearch extends Socio
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['soc_numero','soc_eliminado'], 'integer'],
            [['soc_nombre', 'soc_apellido', 'soc_apellido1', 'soc_apellido2'], 'safe'],
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
        $query = Socio::find()
            ->where(['soc_eliminado' => 0])
            ->leftJoin('categoria c', 'c.cat_id = socio.cat_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'soc_numero' => SORT_DESC, 
                ]
            ]
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'soc_numero' => $this->soc_numero,
            'soc_nombre' => $this->soc_nombre,
            'soc_apellido' => $this->soc_apellido,
            'soc_apellido1' => $this->soc_apellido1,
            'soc_apellido2' => $this->soc_apellido2,
            'soc_eliminado' => $this->soc_eliminado,
        ]);

        $query->andFilterWhere(['like', 'soc_numero', $this->soc_numero])
            ->andFilterWhere(['like', 'soc_nombre', $this->soc_nombre])
            ->andFilterWhere(['like', 'soc_apellido', $this->soc_apellido])
            ->andFilterWhere(['like', 'soc_apellido1', $this->soc_apellido1])
            ->andFilterWhere(['like', 'soc_apellido2', $this->soc_apellido2]);

        return $dataProvider;
    }
}
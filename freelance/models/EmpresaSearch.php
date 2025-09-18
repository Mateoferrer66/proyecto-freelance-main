<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Empresa;

/**
 * EmpresaSearch represents the model behind the search form of `app\models\Empresa`.
 */
class EmpresaSearch extends Empresa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'tdo_id', 'emp_participaciones'], 'integer'],
            [['emp_razon_social', 'emp_numdocide', 'emp_direccion', 'emp_codpostal', 'emp_poblacion', 'emp_telefono', 'emp_fax', 'emp_email', 'emp_regimen_segs', 'emp_ccc_segs', 'emp_tipo_segs', 'emp_razons_segs'], 'safe'],
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
        $query = Empresa::find();

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
            'emp_id' => $this->emp_id,
            'tdo_id' => $this->tdo_id,
            'emp_participaciones' => $this->emp_participaciones,
        ]);

        $query->andFilterWhere(['like', 'emp_razon_social', $this->emp_razon_social])
            ->andFilterWhere(['like', 'emp_numdocide', $this->emp_numdocide])
            ->andFilterWhere(['like', 'emp_direccion', $this->emp_direccion])
            ->andFilterWhere(['like', 'emp_codpostal', $this->emp_codpostal])
            ->andFilterWhere(['like', 'emp_poblacion', $this->emp_poblacion])
            ->andFilterWhere(['like', 'emp_telefono', $this->emp_telefono])
            ->andFilterWhere(['like', 'emp_fax', $this->emp_fax])
            ->andFilterWhere(['like', 'emp_email', $this->emp_email])
            ->andFilterWhere(['like', 'emp_regimen_segs', $this->emp_regimen_segs])
            ->andFilterWhere(['like', 'emp_ccc_segs', $this->emp_ccc_segs])
            ->andFilterWhere(['like', 'emp_tipo_segs', $this->emp_tipo_segs])
            ->andFilterWhere(['like', 'emp_razons_segs', $this->emp_razons_segs]);

        return $dataProvider;
    }
}

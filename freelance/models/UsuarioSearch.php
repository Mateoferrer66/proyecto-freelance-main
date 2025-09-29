<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usuario;

/**
 * UsuarioSearch represents the model behind the search form of `app\models\Usuario`.
 */
class UsuarioSearch extends Usuario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usu_id', 'usu_eliminado'], 'integer'],
            [['usu_nombre', 'usu_apellido', 'usu_email', 'usu_login', 'usu_password', 'usu_estado', 'usu_fecbloqueo'], 'safe'],
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
        $query = Usuario::find();

        $query->andWhere(['usu_eliminado' => 0]);

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
            'usu_id' => $this->usu_id,
            'usu_fecbloqueo' => $this->usu_fecbloqueo,
            'usu_eliminado' => $this->usu_eliminado,
        ]);

        $query->andFilterWhere(['like', 'usu_nombre', $this->usu_nombre])
            ->andFilterWhere(['like', 'usu_apellido', $this->usu_apellido])
            ->andFilterWhere(['like', 'usu_email', $this->usu_email])
            ->andFilterWhere(['like', 'usu_login', $this->usu_login])
            ->andFilterWhere(['like', 'usu_password', $this->usu_password])
            ->andFilterWhere(['like', 'usu_estado', $this->usu_estado]);

        return $dataProvider;
    }
}

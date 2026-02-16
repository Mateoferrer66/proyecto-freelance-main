<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Portafolio;

/**
 * PortafolioSearch represents the model behind the search form of `app\models\Portafolio`.
 */
class PortafolioSearch extends Portafolio
{
    public $soc_codigo;
    public $soc_nombre;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['por_id', 'soc_id', 'por_eliminado'], 'integer'],
            [['por_titulo', 'por_descripcion', 'por_imagenes', 'soc_codigo', 'soc_nombre'], 'safe'],
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
        $query = Portafolio::find()
            ->joinWith(['soc'])
            ->where(['portafolio.por_eliminado' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'por_id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // Enable sorting for related columns
        $dataProvider->sort->attributes['soc_codigo'] = [
            'asc' => ['socio.soc_codigo' => SORT_ASC],
            'desc' => ['socio.soc_codigo' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['soc_nombre'] = [
            'asc' => ['socio.soc_nombre' => SORT_ASC],
            'desc' => ['socio.soc_nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['created_at'] = [
            'asc' => ['portafolio.created_at' => SORT_ASC],
            'desc' => ['portafolio.created_at' => SORT_DESC],
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Grid filtering conditions — prefix with table name to avoid ambiguity
        $query->andFilterWhere([
            'portafolio.por_id' => $this->por_id,
            'portafolio.soc_id' => $this->soc_id,
        ]);

        $query->andFilterWhere(['like', 'portafolio.por_titulo', $this->por_titulo])
            ->andFilterWhere(['like', 'portafolio.por_descripcion', $this->por_descripcion])
            ->andFilterWhere(['like', 'socio.soc_codigo', $this->soc_codigo])
            ->andFilterWhere(['like', 'socio.soc_nombre', $this->soc_nombre]);

        return $dataProvider;
    }
}

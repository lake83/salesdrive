<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Candidates;

/**
 * CandidatesSearch represents the model behind the search form about `app\models\Candidates`
 */
class CandidatesSearch extends Candidates
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['experience', 'is_active'], 'integer'],
            [['birth_date', 'created_at', 'updated_at'], 'date', 'format' => 'd.m.Y'],
            [['name', 'frameworks', 'cv', 'comment'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Candidates::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'experience' => $this->experience,
            'is_active' => $this->is_active,
            'FROM_UNIXTIME(birth_date, "%d.%m.%Y")' => $this->birth_date,
            'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at,
            'FROM_UNIXTIME(updated_at, "%d.%m.%Y")' => $this->updated_at
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'frameworks', $this->frameworks])
            ->andFilterWhere(['like', 'cv', $this->cv])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
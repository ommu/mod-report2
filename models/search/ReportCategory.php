<?php
/**
 * ReportCategory
 *
 * ReportCategory represents the model behind the search form about `ommu\report\models\ReportCategory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 16:13 WIB
 * @modified date 16 January 2019, 16:25 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\report\models\ReportCategory as ReportCategoryModel;

class ReportCategory extends ReportCategoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['cat_id', 'publish', 'name', 'desc', 'creation_id', 'modified_id'], 'integer'],
			[['creation_date', 'modified_date', 'updated_date', 'slug', 'name_i', 'desc_i', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = ReportCategoryModel::find()->alias('t');
        } else {
            $query = ReportCategoryModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'view view', 
			'title title', 
			'description description', 
			'creation creation', 
			'modified modified'
		]);

		$query->groupBy(['cat_id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['name_i'] = [
			'asc' => ['title.message' => SORT_ASC],
			'desc' => ['title.message' => SORT_DESC],
		];
		$attributes['desc_i'] = [
			'asc' => ['description.message' => SORT_ASC],
			'desc' => ['description.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['reports'] = [
			'asc' => ['view.report_all' => SORT_ASC],
			'desc' => ['view.report_all' => SORT_DESC],
		];
		$attributes['resolved'] = [
			'asc' => ['view.report_resolved' => SORT_ASC],
			'desc' => ['view.report_resolved' => SORT_DESC],
		];
		$attributes['unresolved'] = [
			'asc' => ['view.reports' => SORT_ASC],
			'desc' => ['view.reports' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['cat_id' => SORT_DESC],
		]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.cat_id' => $this->cat_id,
			't.name' => $this->name,
			't.desc' => $this->desc,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['trash'])) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        } else {
            if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                $query->andFilterWhere(['IN', 't.publish', [0,1]]);
            } else {
                $query->andFilterWhere(['t.publish' => $this->publish]);
            }
        }

		$query->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'title.message', $this->name_i])
			->andFilterWhere(['like', 'description.message', $this->desc_i])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}

<?php
/**
 * Reports
 *
 * Reports represents the model behind the search form about `ommu\report\models\Reports`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 15:57 WIB
 * @modified date 17 January 2019, 11:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\report\models\Reports as ReportsModel;

class Reports extends ReportsModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['report_id', 'status', 'cat_id', 'user_id', 'reports', 'modified_id'], 'integer'],
			[['app', 'report_url', 'report_body', 'report_message', 'report_date', 'report_ip', 'modified_date', 'updated_date', 'categoryName', 'reporterDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = ReportsModel::find()->alias('t');
        } else {
            $query = ReportsModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'view view', 
			'category.title category', 
			'user user', 
			'modified modified'
		]);

		$query->groupBy(['report_id']);

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
		$attributes['cat_id'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['categoryName'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['reporterDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['comments'] = [
			'asc' => ['view.comments' => SORT_ASC],
			'desc' => ['view.comments' => SORT_DESC],
		];
		$attributes['histories'] = [
			'asc' => ['view.histories' => SORT_ASC],
			'desc' => ['view.histories' => SORT_DESC],
		];
		$attributes['statuses'] = [
			'asc' => ['view.statuses' => SORT_ASC],
			'desc' => ['view.statuses' => SORT_DESC],
		];
		$attributes['users'] = [
			'asc' => ['view.users' => SORT_ASC],
			'desc' => ['view.users' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['report_id' => SORT_DESC],
		]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.report_id' => $this->report_id,
			't.app' => $this->app,
			't.status' => isset($params['status']) ? $params['status'] : $this->status,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.reports' => $this->reports,
			'cast(t.report_date as date)' => $this->report_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		$query->andFilterWhere(['like', 't.report_url', $this->report_url])
			->andFilterWhere(['like', 't.report_body', $this->report_body])
			->andFilterWhere(['like', 't.report_message', $this->report_message])
			->andFilterWhere(['like', 't.report_ip', $this->report_ip])
			->andFilterWhere(['like', 'category.message', $this->categoryName])
			->andFilterWhere(['like', 'user.displayname', $this->reporterDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}

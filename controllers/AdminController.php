<?php
/**
 * AdminController
 * @var $this app\components\View
 * @var $model ommu\report\models\Reports
 *
 * AdminController implements the CRUD actions for Reports model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	Status
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 19 September 2017, 22:58 WIB
 * @modified date 17 January 2019, 11:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
 
namespace ommu\report\controllers;

use Yii;
use app\components\Controller;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;
use ommu\report\models\Reports;
use ommu\report\models\search\Reports as ReportsSearch;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all Reports models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ReportsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		$this->view->title = Yii::t('app', 'Reports');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new Reports model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Reports();
		$model->scenario = Reports::SCENARIO_REPORT;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Report success created.'));
				return $this->redirect(['manage']);
				//return $this->redirect(['view', 'id'=>$model->report_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Report');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Reports model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->scenario = Reports::SCENARIO_REPORT;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Report success updated.'));
				return $this->redirect(['manage']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update {model-class}: {report-body}', ['model-class' => 'Report', 'report-body' => $model->report_body]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single Reports model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		//Reports::insertReport($model->report_url, $model->report_body);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {report-body}', ['model-class' => 'Report', 'report-body' => $model->report_body]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Reports model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Report success deleted.'));
		return $this->redirect(['manage']);
	}

	/**
	 * actionStatus an existing Reports model.
	 * If status is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionStatus($id)
	{
		$model = $this->findModel($id);
		$model->scenario = Reports::SCENARIO_RESOLVED;

		$title = $model->status == 1 ? Yii::t('app', 'Unresolved') : Yii::t('app', 'Resolved');
		$replace = $model->status == 1 ? 0 : 1;
		
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->status = $replace;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Report success updated.'));
				return $this->redirect(['manage']);
			}
		}

		$this->view->title = Yii::t('app', '{title} {model-class}: {report-body}', ['title' => $title, 'model-class' => 'Report', 'report-body' => $model->report_body]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_status', [
			'model' => $model,
			'title' => $title,
		]);
	}

	/**
	 * Finds the Reports model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Reports the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Reports::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}

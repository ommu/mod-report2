<?php
/**
 * Reports (reports)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\AdminController
 * @var $model ommu\report\models\Reports
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 19 September 2017, 22:58 WIB
 * @modified date 17 January 2019, 11:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/admin/dashboard/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->report_body;
} ?>

<div class="reports-view">

<?php
$attributes = [
	[
		'attribute' => 'report_id',
		'value' => $model->report_id,
		'visible' => !$small,
	],
	[
		'attribute' => 'app',
		'value' => $model->app,
	],
	[
		'attribute' => 'cat_id',
		'value' => isset($model->category) ? $model->category->title->message : '-',
	],
	[
		'attribute' => 'report_url',
		'value' => function ($model) {
            if ($model->report_url && $model->report_url != '-') {
                return Html::a($model->report_url, $model->report_url, ['title' => $model->report_url, 'target' => '_blank']);
            }
			return '-';
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'report_body',
		'value' => $model->report_body ? $model->report_body : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'reporterDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'report_date',
		'value' => Yii::$app->formatter->asDatetime($model->report_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'report_ip',
		'value' => $model->report_ip,
		'visible' => !$small,
	],
	[
		'attribute' => 'status',
		'value' => function ($model) {
			$status = $model->status == 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
			$title = $model->status != 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
			return Html::a($status, ['status', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Click to {title}', ['title' => $title]), 'class' => 'modal-btn']);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'reports',
		'value' => function ($model) {
			$reports = $model->reports;
			return Html::a($reports, ['history/admin/manage', 'report' => $model->primaryKey], ['title' => Yii::t('app', '{count} reports', ['count' => $reports])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'comments',
		'value' => function ($model) {
			$comments = $model->getComments(true);
			return Html::a($comments, ['history/comment/manage', 'report' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} comments', ['count' => $comments])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'statuses',
		'value' => function ($model) {
			$statuses = $model->getStatuses(true);
			return Html::a($statuses, ['history/status/manage', 'report' => $model->primaryKey], ['title' => Yii::t('app', '{count} statuses', ['count' => $statuses])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'users',
		'value' => function ($model) {
			$users = $model->getUsers(true);
			return Html::a($users, ['history/user/manage', 'report' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} users', ['count' => $users])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'report_message',
		'value' => $model->report_message ? $model->report_message : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>
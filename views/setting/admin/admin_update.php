<?php
/**
 * Report Settings (report-setting)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\setting\AdminController
 * @var $model ommu\report\models\ReportSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 13:49 WIB
 * @modified date 16 January 2019, 11:10 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Url;

if ($breadcrumb) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/update']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report'), 'url' => ['setting/admin/index']];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Update');
}

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Reset'), 'url' => Url::to(['delete']), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to reset this setting?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="report-setting-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
<?php
/**
 * Report Statuses (report-status)
 * @var $this yii\web\View
 * @var $this ommu\report\controllers\StatusController
 * @var $model ommu\report\models\search\ReportStatus
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 September 2017, 16:03 WIB
 * @modified date 26 April 2018, 09:31 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?php echo $form->field($model, 'status')->checkbox();?>

		<?php echo $form->field($model, 'report_search');?>

		<?php echo $form->field($model, 'reporter_search');?>

		<?php echo $form->field($model, 'report_message');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'updated_ip');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>

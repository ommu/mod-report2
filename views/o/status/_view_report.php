<?php
/**
 * Report Status (report-status)
 * @var $this StatusController
 * @var $model ReportStatus
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @created date 24 August 2017, 06:06 WIB
 * @link https://github.com/ommu/ommu-report
 *
 */

	$category_name = $model->report->category->name ? $model->report->category->title->message : '-';
	$report_url = $model->report->report_url ? $model->report->report_url : '-';
	$report_body = $model->report->report_body ? $model->report->report_body : '-';
	$report_date = $model->report->report_date ? Utility::dateFormat($model->report->report_date, true) : '-';
?>

<ul>
	<li><?php echo Yii::t('phrase', 'Category: $category_name', array('$category_name'=>$category_name));?></li>
	<li><?php echo Yii::t('phrase', 'URL: $report_url', array('$report_url'=>$report_url));?></li>
	<li><?php echo Yii::t('phrase', 'Error: $report_body', array('$report_body'=>$report_body));?></li>
	<li><?php echo Yii::t('phrase', 'Date: $report_date', array('$report_date'=>$report_date));?></li>
<ul>
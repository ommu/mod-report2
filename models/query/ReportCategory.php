<?php
/**
 * ReportCategory
 *
 * This is the ActiveQuery class for [[\ommu\report\models\ReportCategory]].
 * @see \ommu\report\models\ReportCategory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 16 January 2019, 16:26 WIB
 * @modified date 16 January 2019, 16:26 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\query;

class ReportCategory extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportCategory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportCategory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
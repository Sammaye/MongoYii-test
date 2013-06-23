<?php
/**
 * This represents the user actions
 */
class ImageController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
						'users'=>array('*'),
				),
				array('deny',  // deny all users
						'users'=>array('*'),
				),
		);
	}
	
	function actionUser($id){
		$file=EMongoFile::model()->findOne(array('userId' => new MongoId($id)));
		if($file){
			echo $file->getBytes();
		}
	}
	
}
<?php
class ArticleController extends CController{

	/**
	 * @return array action filters
	 */
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules(){
		return array(
			array(
				'allow',
				'actions' => array('delete'),
				'expression' => '$user->isAdmin()'
			),
			array('deny',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('create','edit'),
				'users'=>array('?'),
			),
			array('allow',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex(){
		$this->render('index');
	}

	public function actionCreate(){
		$model=new Article();
		if(isset($_POST['Article'])){
			$model->attributes=$_POST['Article'];
			if($model->validate()&&$model->save()){
				Yii::app()->request->redirect('/article/view',array('id'=>$model->_id));
			}
		}
		$this->render('create',array('model'=>$model));
	}

	public function actionView(){

		$this->render('view',array('model'=>$model));
	}

	public function actionDelete()
	{
		echo "dldeete";
	}
}
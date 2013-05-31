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
				$this->redirect(array('article/view','id'=>$model->_id));
			}
		}
		$this->render('create',array('model'=>$model));
	}

	public function actionEdit($id){
		$model=Article::model()->findOne(array('_id'=>new MongoId($id)));
		if($model&&isset($_POST['Article'])){
			$model->attributes=$_POST['Article'];
			if($model->validate()&&$model->save())
				$this->redirect(array('article/view','id'=>$id));
		}
		$this->render('edit',array('model'=>$model));
	}

	public function actionView($id){
		$model=Article::model()->findOne(array('_id'=>new MongoId($id)));
		$this->render('view',array('model'=>$model));
	}

	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest){
			$model=Article::model()->findOne(array('_id'=>new MongoId($id)));

			if(!Yii::app()->user->isAdmin()&&(Yii::app()->user->id!==$model->userId)){
				// If not admin and this is not the article owner
				echo "not allowed to delete";
				Yii::app()->end();
			}

			if(!$model)
				echo "That Article does not exist!";
			else{
				if($model->delete()){
					if(!isset($_POST['ajax']))
						$this->redirect(array('article/index'));
				}else{
					echo "An error was found while deleting";
				}
			}
		}else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
}
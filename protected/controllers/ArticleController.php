<?php

/**
 * Represents an actual wiki article
 */
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

	/**
	 * This action creates a new wiki article
	 */
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

	/**
	 * This action allows us to edit the wiki article
	 * @param string $id
	 */
	public function actionEdit($id){
		$model=Article::model()->findOne(array('_id'=>new MongoId($id)));
		if($model&&isset($_POST['Article'])){
			$model->attributes=$_POST['Article'];
			if($model->validate()&&$model->save())
				$this->redirect(array('article/view','id'=>$id));
		}
		$this->render('edit',array('model'=>$model));
	}

	/**
	 * This allows us to view a wiki article
	 * @param string $id
	 */
	public function actionView($id){
		$model=Article::model()->findOne(array('_id'=>new MongoId($id)));
		$this->render('view',array('model'=>$model));
	}

	/**
	 * This action deletes an article
	 * @param string $id
	 * @throws CHttpException
	 */
	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest){
			$model=Article::model()->findOne(array('_id'=>new MongoId($id)));

			if(!$model){
				echo "That Article does not exist!";
			}elseif(!Yii::app()->user->isAdmin()&&((string)Yii::app()->user->id!==(string)$model->userId)){
				// If not admin and this is not the article owner
				echo "not allowed to delete";
			}elseif($model->delete()){
				if(!Yii::app()->request->getIsAjaxRequest())
					$this->redirect(array('article/index'));
			}else{
				echo "An error was found while deleting";
			}
		}else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * This action is the one we call from the search box you see at the top of every page 
	 * since we do not always require a $term to be entered we do not require is as dependancy to this function
	 */
	public function actionSearch(){
		$model=new Article;
		$this->render('search',array('model'=>$model));
	}
}
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

			// we are going to purposefully do this the hard way to show people how to do it
			// First we will construct an array of models for create and place them straight into the var
			// In reality we could just use the subdocument validator of course.
			$model->references=array(); $validR=true;
			if(isset($_POST['ArticleReference'])){
				foreach($_POST['ArticleReference'] as $k=>$reference){
					$m=new ArticleReference();
					$m->setAttributes($reference);
					$validR=$m->validate()&&$validR;
					$model->references[]=$m;
				}
			}

			if($model->validate()&&$validR&&$model->save()){
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
			if($model->validate()&&$model->save()){

				// We are gong to divert from the create method of assigning references and do it via atomic calls
				// for each one. Remember you wouldn't create such flimsy repetitive code normally, I am just doing this for
				// demonstration purposes because I lack any imagination to come up with a better example.
				// This example once again demonstrates how MongoYii just provides a glue for you to use the native
				// PHP functions to handle your subdocuments.
				$model->references=array(); $validR=true;
				if(isset($_POST['ArticleReference'])){
					foreach($_POST['ArticleReference'] as $k=>$reference){
						$m=new ArticleReference();
						$m->setAttributes($reference);
						$validR=$m->validate()&&$validR;
						$model->references[]=$m;
					}
				}

				if($validR){
					foreach($model->references as $k=>$v){
						$model->updateAll(array('_id'=>$model->_id),array(
							'$addToSet' => array('references' => $v->getRawDocument())
						));
					}
				}

				$this->redirect(array('article/view','id'=>$id));
			}
		}
		$this->render('edit',array('model'=>$model));
	}

	/**
	 * This allows us to view a wiki article
	 * @param string $id
	 */
	public function actionView($id){
		$model=Article::model()->findOne(array('_id'=>new MongoId($id)));
		if($model) $model->saveCounters(array('views'=>1)); // We viewed this article
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

	/**
	 * This deals with liking an article
	 * @param string $id
	 * @throws CHttpException
	 */
	public function actionLike($id){
		if(Yii::app()->request->getIsAjaxRequest()){
			$model=Article::model()->findBy_id($id);
			if($model){
				$model->like();
				echo json_encode(array('success'=>true));
				Yii::app()->end();
			}
			echo json_encode(array('success'=>false));
		}else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * This deals with disliking an article
	 * @param string $id
	 * @throws CHttpException
	 */
	public function actionDislike($id){
		if(Yii::app()->request->getIsAjaxRequest()){
			$model=Article::model()->findBy_id($id);
			if($model){
				$model->dislike();
				echo json_encode(array('success'=>true));
				Yii::app()->end();
			}
			echo json_encode(array('success'=>false));
		}else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
}
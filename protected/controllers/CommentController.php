<?php
class CommentController extends CController{
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
			array('deny',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('create','edit','delete'),
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
		
		if(Yii::app()->request->getIsPostRequest()){
			
			if(isset($_POST['Comment'])){

				$model=new Comment();
				$model->attributes=$_POST['Comment'];
				if($model->validate()&&$model->save()){		
					if(Yii::app()->request->getIsAjaxRequest()){
						
						// return html render
						ob_start();
						$this->renderPartial('_comment',array('model'=>$model));
						$html=ob_get_contents();
						ob_clean();
						
						echo json_encode(array('success'=>true,'html'=>$html));
						
					}else{
						// redirect
						$this->redirect(array('article/view','id'=>$model->articleId));
					}
				}else{
					if(Yii::app()->request->getIsAjaxRequest()){
						echo json_encode(array('success'=>false,'errors'=>$model->getErrors()));
					}else{
						// Do something fancy with error handling here
					}
				}
			}
			
		}else 
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		
	}
	
	public function actionDelete($id){
		if(Yii::app()->request->getIsPostRequest()){
			
			$model=Comment::model()->findBy_id($id);
			if(
				$model && 
				(Yii::app()->user->isAdmin()||(string)Yii::app()->user->id===(string)$model->userId)
			){
				$model->delete();
				echo json_encode(array('success'=>true));
			}else{
				echo json_encode(array('success'=>false,'errors'=>array('global'=>'Comment not found')));
			} 
				
		}else 
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
}
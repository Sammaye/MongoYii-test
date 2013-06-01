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
	
	/**
	 * This is the action that creates a new comment on a post. The article view page will route an 
	 * ajax call to here. This function will only work from Ajax and no other source, also it will only 
	 * accept a post request. Upon successfully adding a new comment it will return the new comments HTML. 
	 * If it is unsuccessful it will return the model errors.
	 * @throws CHttpException
	 */
	public function actionCreate(){
		
		if(Yii::app()->request->getIsPostRequest()||!Yii::app()->request->getIsAjaxRequest()){

			if(isset($_POST['Comment'])){
				$model=new Comment();
				$model->attributes=$_POST['Comment'];
				if($model->validate()&&$model->save()){		
						
					// return html render
					ob_start();
					$this->renderPartial('_comment',array('model'=>$model));
					$html=ob_get_contents();
					ob_clean();
						
					echo json_encode(array('success'=>true,'html'=>$html));
					Yii::app()->end();
				}else{
					echo json_encode(array('success'=>false,'errors'=>$model->getErrors()));
					Yii::app()->end();
				}
			}else 
				echo json_encode(array('success'=>true,'html'=>''));
			
		}else 
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * This is the action which deletes a comment. It has all the same properties of the action above 
	 * to create a comment except it will only allow a deletion from either an admin or from the owner. 
	 * I could make this into a rule and say that all rules should allow admin access but for now it is 
	 * hard coded like this.
	 * @param string $id
	 * @throws CHttpException
	 */
	public function actionDelete($id){
		if(Yii::app()->request->getIsPostRequest()||!Yii::app()->request->getIsAjaxRequest()){
			
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
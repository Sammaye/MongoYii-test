<?php
/**
 * This represents the user actions
 */
class UserController extends Controller
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
			array(
				'deny',
				'actions' => array('admin'),
				'users' => array('?'),
				'expression' => '$user->isAdmin()' // Does this actually work, adding both users and expression??
			),				
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex(){
		
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];		
		
		$this->render('index',array('model'=>$model));
	}

	/**
	 * This creates our new user and also logs them in if the creation is successfull
	 */
	public function actionCreate(){
		$model=new User;
		if(isset($_POST['User'])){
			$model->attributes=$_POST['User'];
			if($model->validate()&&$model->save()){
				$identity=new UserIdentity($model->username,'');
				$identity->setId($model->_id); // we set the id of the identity to the _id of the user
				$identity->errorCode=UserIdentity::ERROR_NONE;
				if(Yii::app()->user->login($identity,0)){
					$this->redirect('site/index');
				}
			}
		}
		$this->render('create',array(
			'model'=>$model
		));
	}
	
	public function actionEdit(){
		
		$model=User::model()->findOne(array('_id'=>Yii::app()->user->id));
		if($model===null)
			throw new CHttpException(403, 'You are not logged in');
		
		if($file=EMongoFile::populate($model,'avatar')){
			if($oldFile=EMongoFile::model()->findOne(array('userId'=>Yii::app()->user->id)))
				$oldFile->delete();
			$file->userId=$model->_id;
			if($file->save()){
				Yii::app()->user->setFlash('success', "Avatar Changed!");
			}				
		}
		
		if(isset($_POST['User'])){
			$model->attributes=$_POST['User'];
			if($model->validate()){
				echo "sdjgfldsflksdfkl;sdfgkldsgfkldsmgkdg";
			}else{
				echo "poop";
			}
		}
				
		$this->render('edit',array(
			'model' => $model		
		));
	}

	/**
	 * This lists and allows us to search across all users. Only accessible to Admins
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Displays the users profile and shows their articles and comments and counts etc
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	  	$model = User::model()->findBy_id($id);
		$this->render('view',array(
			'model'=>$model,
		));
	}
}
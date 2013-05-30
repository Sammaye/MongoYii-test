<?php
/**
 * This is the User controller taking care of all the access to the user table in the database.
 *
 * @author John Eskilsson & Sam Millman
 * @version 0.1
 * @package site.backend.controllers
 */
class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/admin';

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

	public function actionCreate(){
		$model=new User;
		if(isset($_POST['User'])){
			$model->attributes=$_POST['User'];
			if($model->validate()){
				if($model->save()){
			        $identity=new UserIdentity($model->username,'');
			        //$identity->setID($model->id); /* had to add WebUser::setID() since WebUser::$_id is private */
			        $identity->errorCode=UserIdentity::ERROR_NONE;
			        if(Yii::app()->user->login($identity,0)){
			        	Yii::app()->request->redirect('/');
			        }
				}
			}
		}
		$this->render('create',array(
			'model'=>$model
		));
	}

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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	  $model = $this->loadModel($id);

		$this->render('view',array(
			'model'=>$model,
		));
	}

}
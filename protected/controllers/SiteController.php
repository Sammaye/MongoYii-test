<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

//		$user=new User;
//		$user->_id='1002';
//		$user->username='sammaye';
//		$user->save();
		$user = User::model()->findOne(array('_id'=>'1002'));
		
		var_dump(Yii::app()->mongodb->getDocumentCache('User'));
//		echo ($user->_id."\n");
//		print_r($user->delete());
//		unset($user);
//		$user = User::model()->findOne(array('_id'=>'1002'));
//		echo ($user->_id . "\n");
//		exit;

//		$u=new User();
//		$u->username='samaye';
//		$u->active=true;
//		$u->save();

//		$u=new User();
//		$u->username='samaye';
//		$u->active=true;
//		$u->save();
//
//		$u=new User();
//		$u->username='samaye';
//		$u->active=true;
//		$u->save();
//
//		$u=new User();
//		$u->username='samaye';
//		$u->active=true;
//		$u->save();
//
//		$u=new User();
//		$u->username='samaye';
//		$u->active=false;
//		$u->save();
//
		//$u=new User();
		//$u->username='samaye';
		//$u->boards = array(array('dealclub' => 123));
		//$u->source = 'cheese';
		//$u->active=false;
		//var_dump($u->save());

		$c = new EMongoCriteria();
		$c->addCondition('username', 'samaye');
		$c->addCondition('active', true);
		$c->skip=1;
		$c->limit = 5;
		//var_dump(User::model()->find($c)->count());

		//var_dump(User::model()->findOne());

		//$u=new EMongoDataProvider('User', array('pagination' => array('pageSize' => 20), 'criteria' => array('condition' => array('boards.dealclub' => 123, 'source' => array('$ne' => 'cheese')))));
		//var_dump($u);
		//var_dump($u->getTotalItemCount());

		//var_dump(Yii::app()->mongodb);

		/*
		$u = new User();
		$u->username = 'd';
		//var_dump($u->username);

		$model->attributes=array(
			array(
				'road' => 'elm',
				'town' => 'b'
			)
		);
		// validate user input and redirect to the previous page if valid
		if($u->validate()){
			echo "valid";
			$u->save();
		}

		$d = new Other;
		$d->username = 'e';

		$c = User::model()->findOne();
		var_dump($c->others);

		var_dump($c->others->count());
		foreach($c->others as $ot) var_dump($ot);

		$d->otherId = $c->_id;

		// validate user input and redirect to the previous page if valid
		if($d->validate()){
			echo "valid";
			$d->save();
		}

		$e = Other::model()->findOne();
		var_dump($e);
		//foreach($c as $row)
			//var_dump($row);
		*/

		/*
		$u=new User();
		$u->setAttributes(array('_id' => new MongoId(), 'username' => 'sammaye', 'poop' => true),false);

		var_dump($u->getAttributes());
		*/

//		Yii::app()->mongodb->sites->drop();
//        Yii::app()->mongodb->sites->insert(
//            array('_id' => 'example.com', "title" => "Example com")
//        );
//
//        $site = Site::model()->findOne(array('_id' => 'example.com'));
//        var_dump($site);
//        var_dump($site->title=='Example com');
//        var_dump(array('_id'=>'example.com', 'title'=>'Example com')===$site->getAttributes());
//       	//$this->assertTrue($site instanceof Site);
//
//        //$this->assertEquals('Example com', $site->title);
//               // Change title to org
//        $site->attributes = array('_id' => $site->_id, 'title' => 'Example org');
//        var_dump($site->getRawDocument());
//        var_dump($site->save());
//        //$this->assertTrue($site->save());
//
//        $site = Site::model()->findOne(array('_id' => 'example.com', 'title' => 'Example org'));
//        var_dump($site);
//        var_dump($site instanceof Site);
//        var_dump('Example org'==$site->title);
        //$this->assertTrue($site instanceof Site);
        //$this->assertEquals('Example org', $site->title);

		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
<?php

/**
 * Represents the user and his/her data
 */
class User extends EMongoDocument{

	/** @virtual */
	public $avatar;
	
	public $username;
	public $password;

	public $email;
	public $group; // 2 is Admin

	public $totalArticles=0;
	public $totalComments=0;
	
	public $profile;

	public function groups(){
		return array(
			'User',
			'VIP',
			'Admin' // this is position 2
		);
	}

	public function behaviors(){
	  return array(
  		'EMongoTimestampBehaviour' => array(
  			'class' => 'EMongoTimestampBehaviour' // Adds a handy create_time and update_time
  	  ));
	}

	function rules(){
		return array(
			array('username,email,password', 'required'),
			array('username', 'length', 'max' => 20),
				
			array('profile','subdocument','type'=>'one','rules'=>array(
				array('title','length','max'=>12,'tooLong'=>'Second title is bad'),
				array('url','url')		
			)),
				
			//array('email', 'email'), // Removed this so I could test some bugs
			array('_id, username, email, group', 'safe', 'on'=>'search'),
		);
	}

	function collectionName(){
		return 'user';
	}

	function relations(){
		return array(
			'articles' => array('many','Article','userId'),
			'comments' => array('many','Comment','userId')		
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function attributeLabels(){
		return array(
			'profile[title]' => 'First title'
		);
	}
	
	public function search(){
		$criteria = new EMongoCriteria;
		
		if($this->_id!==null)
			$criteria->compare('_id', new MongoId($this->_id));
		//$criteria->compare('__v', $this->__v);
		return new EMongoDataProvider(get_class($this), array(
				'criteria' => $criteria,
		));
		
	}

	/**
	 * Hashes our password, taken straight from the tutorial
	 * @return string
	 */
	function hashPassword(){
		return crypt($this->password,$this->blowfishSalt());
	}

	function beforeSave(){
		$this->password=$this->hashPassword(); // lets hash that shiz
		return parent::beforeSave();
	}

	/**
	 * Generate a random salt in the crypt(3) standard Blowfish format.
	 *
	 * @param int $cost Cost parameter from 4 to 31.
	 *
	 * @throws Exception on invalid cost parameter.
	 * @return string A Blowfish hash salt for use in PHP's crypt()
	 */
	function blowfishSalt($cost = 13)
	{
		if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
			throw new Exception("cost parameter must be between 4 and 31");
		}
		$rand = array();
		for ($i = 0; $i < 8; $i += 1) {
			$rand[] = pack('S', mt_rand(0, 0xffff));
		}
		$rand[] = substr(microtime(), 2, 6);
		$rand = sha1(implode('', $rand), true);
		$salt = '$2a$' . sprintf('%02d', $cost) . '$';
		$salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
		return $salt;
	}
}
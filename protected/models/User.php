<?php

class User extends EMongoDocument{

	public $username;
	public $password;

	public $email;

	function rules(){
		return array(
			array('username,email,password', 'required'),
			array('username', 'length', 'max' => 20),
			array('email', 'email'),
			array('_id, username', 'safe', 'on'=>'search'),
		);
	}

	function collectionName(){
		return 'user';
	}

	function relations(){
		return array();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	function hashPassword(){
		return crypt($this->password,$this->blowfishSalt());
	}

	function beforeSave(){
		$this->password=$this->hashPassword();
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
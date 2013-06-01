<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

	private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$record=User::model()->findOne(array('username' => $this->username));
		if ($record === null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else if ($record->password !== crypt($this->password, $record->password)) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		} else {
			$this->_id = $record->_id;
			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;
	}

	function getId(){
		return $this->_id;
	}
	
	function setId($_id){
		$this->_id=$_id;
	}
}
<?php

/**
 * This is a custom CWebUser designed to to be used with the UserIdentity in this folder and the 
 * EMongoSession to provide full MongoDB user sessions
 */
class EWebUser extends CWebUser{

    protected $_model;

    /**
     * Detects if the user is an admin
     * @return boolean
     */
    function isAdmin(){
        $user = $this->loadUser();
        if ($user)
           return $user->group==2;
        return false;
    }

    // Load user model.
    protected function loadUser()
    {
        if ( $this->_model === null ) {
                $this->_model = User::model()->findByPk( $this->id );
        }
        return $this->_model;
    }
}
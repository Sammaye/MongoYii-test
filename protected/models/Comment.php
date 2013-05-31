<?php
class Comment extends EMongoDocument{

	function collectionName(){
		return 'comment';
	}

	public function behaviors(){
	  return array(
  		'EMongoTimestampBehaviour' => array(
  			'class' => 'EMongoTimestampBehaviour'
  	  ));
	}

	public function relations(){
		return array(
			'author' => array('one','User','_id','on'=>'userId')
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	function rules(){
		return array(
			array('body', 'required'),
			array('body', 'length', 'max' => 500)
		);
	}

	function beforeSave(){
		if($this->userId===null) $this->userId = Yii::app()->user->id;
		return parent::beforeSave();
	}

	function afterSave(){
		if($this->getIsNewRecord())
			$this->author->saveCounters(array('totalComments'=>1));
		return parent::afterSave();
	}

	function afterDelete(){
		if($this->author->totalComments>1)
			$this->author->saveCounters(array('totalComments'=>-1));
		else{
			$this->author->totalComments=0; // $inc won't work with 0...I should think of a decent way to fix that...
			$this->author->save();
		}
		return parent::afterDelete();
	}
}
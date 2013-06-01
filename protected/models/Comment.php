<?php

/**
 * Represents a comment and its data
 */
class Comment extends EMongoDocument{

	// No pre-defined schema here!
	// public $userId // Author id
	// public $articleId // Article which this is attached to
	// public $body // content of the comment
	
	
	// Due to how the __get and __set work this won't actually function
	// Wouldn't mind changing the way MongoYii works to make this function as it should
	// but then this would only for non-defined vars which would kind of suck...
	// @see https://github.com/Sammaye/MongoYii/issues/70
	function getArticleId(){
		return new MongoId($this->articleId);
	}
	
	function collectionName(){
		return 'comment';
	}

	public function behaviors(){
	  return array(
  		'EMongoTimestampBehaviour' => array(
  			'class' => 'EMongoTimestampBehaviour' // Adds a handy create_time and update_time
  	  ));
	}

	public function relations(){
		return array(
			'author' => array('one','User','_id','on'=>'userId'),
			'article' => array('one','Article','_id','on'=>'articleId')
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
			array('body,articleId', 'required'),
			array('body', 'length', 'max' => 500),
			array('articleId','EMongoExistValidator', 'className'=>'Article', 'attributeName'=>'_id', 'mongoId'=>true)
		);
	}

	function beforeSave(){
		if($this->userId===null) $this->userId = Yii::app()->user->id; // If there is no user id here lets just add the one in session
		$this->articleId=new MongoId($this->articleId);
		return parent::beforeSave();
	}

	function afterSave(){
		if($this->getIsNewRecord()){
			$this->author->saveCounters(array('totalComments'=>1));
			$this->article->saveCounters(array('totalComments'=>1));
		}
		return parent::afterSave();
	}

	/**
	 * After delete will deInc all of the pre-aggregated variables needed in each model
	 * @see EMongoDocument::afterDelete()
	 */
	function afterDelete(){
		if($this->author->totalComments>1){
			$this->author->saveCounters(array('totalComments'=>-1));
		}else{
			$this->author->totalComments=0; // $inc won't work with 0...I should think of a decent way to fix that...
			$this->author->save();
		}

		if($this->article->totalComments>1)
			$this->article->saveCounters(array('totalComments'=>-1));
		else{
			$this->article->totalComments=0; // $inc won't work with 0...I should think of a decent way to fix that...
			$this->article->save();
		}
		return parent::afterDelete();
	}
}
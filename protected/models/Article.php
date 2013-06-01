<?php
class Article extends EMongoDocument{

	// We are going to predefine the schema here
	public $userId;
	public $title;
	public $body;

	/**
	 * This will contain a list of users to committed revisions to this article
	 * including the time of the revision
	 * @var array
	 */
	public $revisions=array();

	public $totalComments=0;

	function collectionName(){
		return 'article';
	}

	public function behaviors(){
	  return array(
  		'EMongoTimestampBehaviour' => array(
  			'class' => 'EMongoTimestampBehaviour'
  	  ));
	}

	public function relations(){
		return array(
			'author' => array('one','User','_id','on'=>'userId'),
			'comments' => array('many','Comment','articleId')
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
			array('title,body','required'),
			array('title','length','max'=>255),
			array('body','length','max'=>2500)
		);
	}

	function beforeSave(){
		if($this->userId===null) $this->userId = Yii::app()->user->id;

		if(!$this->getIsNewRecord()){
			// If this is not a new recrd then it is being edited
			// Lets form a subdocument for revisions and place it in
			$revision = array(
				'userId' => empty(Yii::app()->user->id) ? Yii::app()->request->getUserHostAddress() : Yii::app()->user->id,
				'time' => new MongoDate()
			);
			$this->revisions[]=$revision;
		}
		return parent::beforeSave();
	}

	function afterSave(){
		if($this->getIsNewRecord())
			$this->author->saveCounters(array('totleArticles'=>1));
		return parent::afterSave();
	}

	function afterDelete(){
		if($this->author->totalArticles>1)
			$this->author->saveCounters(array('totalArticles'=>-1));
		else{
			$this->author->totalArticles=0; // $inc won't work with 0...I should think of a decent way to fix that...
			$this->author->save();
		}
		Comment::model()->deleteAll(array('articleId'=>new MongoId($this->_id)));
		return parent::afterDelete();
	}

	function getBodyPreview($count=250){
		if(strlen($this->body)>$count)
			return substr($this->body, 0, $count-3).'...';
		else
			return $this->body;
	}
}
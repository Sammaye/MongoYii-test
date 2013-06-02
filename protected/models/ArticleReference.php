<?php

/**
 * This model represents an article reference. These are basically URLs for support the facts within the article.
 * 
 * This will be stored as a subdocument on the article record.
 */
class ArticleReference extends EMongoModel{

	public $caption;
	public $url;
	
	public function rules(){
		return array(
			array('caption,url','required'),
			array('caption','length','max'=>255),
			array('url','length','max'=>2000),
			array('url','url')		
		);
	}
	
	public function renderUrl(){
		echo CHtml::link($caption,$url);
	}
}
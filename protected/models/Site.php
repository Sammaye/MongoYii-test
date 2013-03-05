<?php
class Site extends EMongoDocument
{
    public $title;

    public function collectionName()
    {
        return 'sites';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('_id, title', 'required'),
        );
    }
}
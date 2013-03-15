<?php
/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property string $id
 * @property string $menu_id
 * @property string $label
 * @property string $title
 * @property string $url
 * @property integer $pagina_id
 * @property integer $attivo
 * @property integer $posizione
 * @property string $_inserted
 * @property string $_updated
 *
 * The followings are the available model relations:
 * @property Menu $menu
 * @property Menu[] $menus
 */
class Menu extends EMongoDocument
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	function collectionName()
	{
		return 'menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label, title', 'required'),
			array('attivo, posizione', 'numerical', 'integerOnly'=>true),
			array('menu_id, pagina_id', 'length', 'max'=>10),
			array('label', 'length', 'max'=>100),
			array('url', 'length', 'max'=>255),
			array('_inserted, _updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, menu_id, label, title, url, pagina_id, attivo, posizione, _inserted, _updated', 'safe', 'on'=>'search'),
		);
	}



	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'menu_id' => 'Menu',
			'label' => 'label',
			'title' => 'title',
			'url' => 'Url',
			'pagina_id' => 'Pagina',
			'attivo' => 'Attivo',
			'posizione' => 'Posizione',
			'_inserted' => 'Data inserimento',
			'_updated' => 'Data ultima modifica',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		/*$criteria=new EMongoCriteria();

		$criteria->compare('id',$this->id);
		$criteria->compare('menu_id',$this->menu_id,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('url',$this->url);
		$criteria->compare('pagina_id',$this->pagina_id,true);
		$criteria->compare('attivo',$this->attivo);
		$criteria->compare('posizione',$this->posizione);
		$criteria->compare('_inserted',$this->_inserted,true);
		$criteria->compare('_updated',$this->_updated,true);
		$criteria->sort = array('label'=>-1);
		$criteria->limit = 2;

		print_r($criteria);*/


		return new EMongoDataProvider($this, array(
		    //'criteria' => (array)$criteria,
			/*'criteria' => array(
				'condition' => array('url'=>array('$lt'=>'bbb'),
			),
			),*/
		    /* All other options */
		));
	}

}
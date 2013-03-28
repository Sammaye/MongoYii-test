<?php
/**
 * CCheckBoxColumn class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.grid.CGridColumn');

/**
 * CCheckBoxColumn represents a grid view column of checkboxes.
 *
 * By default, the checkboxes rendered in data cells will have the key values associated with
 * the data models in the corresponding rows. One may change this by setting either {@link name}
 * {@link value}.
 *
 * CCheckBoxColumn supports single selection and multiple selection. The mode is determined according
 * to {@link CGridView::selectableRows}. When in multiple selection mode, the header cell will display
 * an additional checkbox, clicking on which will check or uncheck all of the checkboxes in the data cells.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CCheckBoxColumn.php 99 2010-01-07 20:55:13Z qiang.xue $
 * @package zii.widgets.grid
 * @since 1.1
 */
class CCheckBoxColumn extends CGridColumn
{
	/**
	 * @var string the attribute name of the data model. The corresponding attribute value will be rendered
	 * in each data cell as the checkbox value. Note that if {@link value} is specified, this property will be ignored.
	 * @see value
	 */
	public $name;
	/**
	 * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
	 * in each data cell as the checkbox value. In this expression, the variable
	 * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $value;
	/**
	 * @var array the HTML options for the data cell tags.
	 */
	public $htmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the header cell tag.
	 */
	public $headerHtmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the footer cell tag.
	 */
	public $footerHtmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the checkboxes.
	 */
	public $checkBoxHtmlOptions=array();

	/**
	 * Initializes the column.
	 * This method registers necessary client script for the checkbox column.
	 */
	public function init()
	{
		$name="{$this->id}\\[\\]";
		if($this->grid->selectableRows==1)
			$one="\n\tjQuery(\"input:not(#\"+$(this).attr('id')+\")[name='$name']\").attr('checked',false);";
		else
			$one='';
		$js=<<<EOD
jQuery('#{$this->id}_all').live('click',function(){
	var checked=this.checked;
	jQuery("input[name='$name']").each(function() {
		this.checked=checked;
	});
});
jQuery("input[name='$name']").click(function() {
	jQuery('#{$this->id}_all').attr('checked', jQuery("input[name='$name']").length==jQuery("input[name='$name'][checked=true]").length);{$one}
});
EOD;
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,$js);
	}

	/**
	 * Renders the header cell content.
	 * This method will render a checkbox in the header when {@link CGridView::selectableRows} is greater than 1.
	 */
	protected function renderHeaderCellContent()
	{
		if($this->grid->selectableRows>1)
			echo CHtml::checkBox($this->id.'_all',false);
		else
			parent::renderHeaderCellContent();
	}

	/**
	 * Renders the data cell content.
	 * This method renders a checkbox in the data cell.
	 * @param integer the row number (zero-based)
	 * @param mixed the data associated with the row
	 */
	protected function renderDataCellContent($row,$data)
	{
		if($this->value!==null)
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		else if($this->name!==null)
			$value=CHtml::value($data,$this->name);
		else
			$value=$this->grid->dataProvider->keys[$row];
		$options=$this->checkBoxHtmlOptions;
		$options['value']=$value;
		$options['id']=$this->id.'_'.$row;
		echo CHtml::checkBox($this->id.'[]',false,$options);
	}
}

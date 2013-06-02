<?php 
ob_start();
$this->renderPartial('_articleReference',array('model'=>new ArticleReference(), 'k' => 0));
$html=CJavaScript::encode(ob_get_contents());
ob_end_clean();
$js=<<<JS

$(function(){
	var inc=$('.references .reference').length;

	$('#add_reference').on('click',function(e){
		e.preventDefault();
		var html=$html;
		html=html.replace(/\[0\]/g,'['+inc+']');
		$('.references').append(html);
		inc++;
	});
});

JS;
Yii::app()->getClientScript()->registerScript('references', $js);
?>

<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'article-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div>
		<?php echo $form->label($model,'title') ?>
		<?php echo $form->textField($model,'title') ?>
	</div>

	<div>
		<?php echo $form->label($model,'body') ?>
		<?php echo $form->textArea($model,'body') ?>
	</div>
	
	<div class="references">
		<?php foreach($model->references as $k=>$v){ 
			$r=new ArticleReference();
			$r->setAttributes($v); 
			$this->renderPartial('_articleReference',array('model'=>$r, 'k' => $k));
		} ?>
	</div>
	<a href="#" id="add_reference">Add reference</a>
	<div class="clear"></div>

	<?php echo Chtml::submitButton('Create Article') ?>

	<?php $this->endWidget() ?>
</div>
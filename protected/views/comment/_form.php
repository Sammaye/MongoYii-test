<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'article-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div>
		<?php echo $form->textArea($model,'body') ?>
	</div>
	<?php echo Chtml::submitButton('Add Comment') ?>
	<?php $this->endWidget() ?>
</div>
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

	<?php echo Chtml::submitButton('Create Article') ?>

	<?php $this->endWidget() ?>
</div>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'user-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div>
		<?php echo $form->label($model,'username') ?>
		<?php echo $form->textField($model,'username') ?>
	</div>
	<div>
		<?php echo $form->label($model,'email') ?>
		<?php echo $form->textfield($model,'email') ?>
	</div>
	<div>
		<?php echo $form->label($model,'password') ?>
		<?php echo $form->passwordField($model,'password') ?>
	</div>
	<div>
		<?php echo CHtml::submitButton('Register') ?>
	</div>
	<?php $this->endWidget(); ?>
</div>
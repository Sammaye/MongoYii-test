<h1>Upload Avatar</h1>

<?php 
foreach(Yii::app()->user->getFlashes() as $key => $message) {
	echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}
?>

<img src="<?php echo $this->createUrl('image/user', array('id'=>$model->_id)); ?>"/>

<?php $form=$this->beginWidget('CActiveForm', array('id'=>'profile-form', 'htmlOptions' => array('enctype'=>'multipart/form-data'))); ?>

<?php echo $form->label($model,'profile[title]') ?>
<?php echo $form->textField($model,'profile[title]') ?>
<?php echo $form->error($model,'profile[title]') ?>

<?php echo $form->label($model,'profile[url]') ?>
<?php echo $form->textField($model,'profile[url]') ?>
<?php echo $form->error($model,'profile[url]') ?>

<?php echo $form->fileField($model, 'avatar') ?>
<?php echo CHtml::submitButton('Set Avatar') ?>
<?php $this->endWidget(); ?>

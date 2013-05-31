<?php

$d=<<<COMMENTS

$(function(){


});

COMMENTS;
Yii::app()->getClientScript()->registerScript('comments', $d);

if(Yii::app()->user->isAdmin()||Yii::app()->user->id===$model->userId)
	echo CHtml::linkButton('Delete', array('submit' => array('/article/delete', 'id' => $model->_id)))
?>
<?php echo CHtml::link('Edit', array('/article/edit', 'id'=>$model->_id)) ?>

<h1><?php echo $model->title ?></h1>
<div><p>Authored by <?php echo $model->author->username ?> on <?php echo date('d/m/Y h:i:s a',$model->create_time->sec) ?></p></div>
<div><?php echo CHtml::encode($model->body)?></div>

<div>
	<h2>Revisions</h2>
	<?php if(count($model->revisions) > 0){ ?>
		<ul>
			<?php foreach($model->revisions as $revision){ ?>
				<li>
					Revised by <?php
						$u=User::model()->findOne(array('_id'=>$revision['userId']));
						if($u)
							echo $u->username;
						else
							echo $revision['userId'];
					?> at
					<?php echo date('d/m/Y h:i:s',$revision['time']->sec) ?>
				</li>
			<?php } ?>
		</ul>
	<?php }else{ ?>
		None listed yet
	<?php } ?>
</div>

<!-- Comments -->

<div>
	<?php if($model->totalComments>=1): ?>
		<h3>Comments (<?php echo $model->totalComments>1?$model->totalComments.' comments':'1 comment'?>)</h3>

		<?php $this->renderPartial('_comments', array(
			'model'=>$model,
			'coments'=>$model->comments
		)); ?>
	<?php endif; ?>

	<h3>Leave a Comment</h3>
	<?php
	$comment = new Comment();
	$comment->articleId=$model->_id;
	$this->renderPartial('/comment/_form', array(
		'model' => $comment
	)) ?>
</div><!-- Comment list -->

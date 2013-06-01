<?php

if($model===null){
	throw new CHttpException(404, 'Could not find the article you were looking for');
	return;
}

$u=$this->createUrl('comment/create');
$d=<<<COMMENTS

$(function(){

	$('input[name=yt0]').on('click',function(e){
		e.preventDefault();
		$.ajax({
			url: '$u',
			data: $(this).parents('.leave_comment').find('input,textarea').serialize(),
			dataType: 'json',
			type: 'POST'
		}).done(function(data){
			if(data.success){
				$('.comments').append(data.html);
			}else{
				// Show some error messages
			}
		});
	});

});

COMMENTS;
Yii::app()->getClientScript()->registerScript('addComment', $d);
if(Yii::app()->user->isAdmin()||(string)Yii::app()->user->id===(string)$model->userId)
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
	<h3>Comments <?php if($model->totalComments>0): ?>(<?php echo $model->totalComments>1?$model->totalComments.' comments':'1 comment'?>)<?php endif; ?></h3>

	<div class="comments">
		<?php $this->renderPartial('_comments', array(
			'model'=>$model,
			'comments'=>$model->comments
		)); ?>
	</div>

	<h3>Leave a Comment</h3>
	<div class='leave_comment'>
		<?php
		$comment = new Comment();
		$comment->articleId=$model->_id;
		$this->renderPartial('/comment/_form', array(
			'model' => $comment
		)) ?>
	</div>
</div><!-- Comment list -->

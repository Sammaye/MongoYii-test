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
	
	$('li.like a,li.dislike a').on('click',function(e){
		e.preventDefault();
		$.ajax({
			url: $(this).attr('href'),
			dataType: 'json',
			type: 'post'
		}).done(function(data){
			if(data.success){
				// Whoop
			}else{
				// Poop
			}
		});
	});

});

COMMENTS;
Yii::app()->getClientScript()->registerScript('addComment', $d);

?><div class="container">
<div class="span-18 wiki_body_container">
<h1 class="wiki_title"><?php echo $model->title ?></h1>
<ul class="wiki_vote_bar">
	<li class="like"><a href="<?php echo $this->createUrl('article/like', array('id'=>$model->_id)) ?>"><span><?php echo count($model->likes) ?></span></a></li>
	<li class="dislike"><a href="<?php echo $this->createUrl('article/dislike', array('id'=>$model->_id)) ?>"><span><?php echo count($model->dislikes) ?></span></a></li>
</ul>
<div class="clear"></div>
<div class="wiki_boody"><?php echo CHtml::encode($model->body)?></div>

<!-- Comments -->

<div class="wiki_comments">
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
</div>
<div class="span-5 last wiki_side_bar">
	<ul class='side_menu wiki_side_bar_first'>
		<li><?php echo CHtml::link('Update this article', array('/article/edit', 'id'=>$model->_id)) ?></li>
		<li><?php 
		if(Yii::app()->user->isAdmin()||(string)Yii::app()->user->id===(string)$model->userId)
			echo CHtml::linkButton('Delete this article', array('submit' => array('/article/delete', 'id' => $model->_id)))
		?></li>
	</ul>
	
	<ul class='side_menu menu_separated'>
		<li><b>Written by</b>: <?php echo CHtml::link($model->author->username,array('user/view','id'=>$model->author->_id)) ?></li>
		<li><b>Views</b>: <?php echo $model->views ?></li>
		<li><b>Created on</b>: <?php echo date('d/m/Y h:i:s a',$model->create_time->sec) ?></li>
		<li><b>Last Updated</b>: <?php echo $model->update_time instanceof MongoDate ? date('d/m/Y h:i:s a',$model->create_time->sec) : '<i>never</i>' ?>
	</ul>
	
	<?php if($model->usersLiked->count()>0){
		echo CHtml::tag('h3',array(),'Liked by');
		echo CHtml::openTag('ul',array('class'=>'side_menu'));
		foreach($model->usersLiked as $user){
			echo CHtml::tag('li',array(),CHtml::link($user->username,array('user/view','id'=>$user->_id)));
		}
		echo CHtml::closeTag('ul');
	} ?>
	
	<?php if($model->usersDisliked->count()>0){
		echo CHtml::tag('h3',array(),'Disliked by');
		echo CHtml::openTag('ul',array('class'=>'side_menu'));
		foreach($model->usersDisliked as $user){
			echo CHtml::tag('li',array(),CHtml::link($user->username,array('user/view','id'=>$user->_id)));
		}
		echo CHtml::closeTag('ul');
	} ?>	
</div>
</div>

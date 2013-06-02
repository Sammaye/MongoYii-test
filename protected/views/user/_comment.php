<div class="comment">
	<div><?php echo CHtml::link($data->author->username, array('user/view','id'=>$data->author->_id)) ?> on <?php echo date('d/m/Y h:i:sa', $data->create_time->sec) ?></div>
	<p><?php echo nl2br(CHtml::encode($data->body)) ?></p>
	<p>In reply to: <?php echo CHtml::link($data->article->title, array('article/view','id'=>$data->article->_id)) ?></p>
</div>
<div class="wiki_article_list_view">
	<h2><?php echo Chtml::link($data->title, array('/article/view','id'=>$data->_id)); ?></h2>
	<p><?php echo Chtml::encode($data->getBodyPreview()); ?></p>
	<p class="info_bar">Written by <?php echo Chtml::link($data->author->username, array('/user/view', 'id'=>$data->author->_id)); ?></p>
</div>
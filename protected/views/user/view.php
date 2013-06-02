<div class="container user_profile_container">
<div class="span-20">
	<h1><?php echo $model->username ?></h1>
	<div>Joined: <?php echo date('d/m/Y h:i:sa',$model->create_time->sec) ?></div>
	
	<?php if($model->totalArticles>0){ ?>
		<h2><?php echo $model->totalArticles>1?$model->totalArticles.' articles':$model->totalArticles.' article' ?></h2>
		<?php
		
		$dataProvider=new EMongoDataProvider('Article', array(
			'criteria' => array('userId'=>$model->_id)
		));
		
		$this->widget('zii.widgets.CListView', array(
		    'dataProvider'=>$dataProvider,
		    'itemView'=>'_article',  // Hmmm, for some reason I cannot do /article/_article :\
		)); ?>		
	<?php } ?>
	
	<?php if($model->totalComments>0){ ?>
		<h2><?php echo $model->totalComments>1?$model->totalComments.' comments':$model->totalComments.' comments' ?></h2>	
		<?php
		
		$dataProvider=new EMongoDataProvider('Comment', array(
			'criteria' => array('userId'=>$model->_id)
		));
		
		$this->widget('zii.widgets.CListView', array(
		    'dataProvider'=>$dataProvider,
		    'itemView'=>'_comment',  
		)); ?>		
	<?php } ?>
</div>
</div>
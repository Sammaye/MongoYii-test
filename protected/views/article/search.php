<h1>Search results for <?php echo CHtml::encode(isset($_GET['term'])?$_GET['term']:'everything') ?></h1>

<?php
$dataProvider=$model->search(isset($_GET['term']) ? $_GET['term'] : '');

$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',   // refers to the partial view named '_post'
));
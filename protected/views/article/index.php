<div class="container view_articles_container">
<h1>Wiki Articles</h1>
<div class="span-18">
<?php

$dataProvider=new EMongoDataProvider('Article');

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',   // refers to the partial view named '_post'
)); ?>
</div>
</div>
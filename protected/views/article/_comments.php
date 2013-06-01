<?php
foreach($comments as $comment){
	$this->renderPartial('/comment/_comment',array('model'=>$comment));
}

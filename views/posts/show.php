<?php $this->setLayoutVar('title', $post['name']) ?>

<?php echo $this->render('posts/post', array('post' => $post)); ?>
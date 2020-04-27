<?php $this->setLayoutVar('title', $performance['name']); ?>

<h2><?php echo $this->escape($performance['name']); ?></h2>

<div id="posts">
  <?php foreach ($posts as $post) : ?>
    <?php echo $this->render('posts/post', array('post' => $post)); ?>
  <?php endforeach; ?>
</div>
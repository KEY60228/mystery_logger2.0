<?php $this->setLayoutVar('title', $user['name']) ?>

<h2><?php echo $this->escape($user['name']); ?></h2>

<div id="posts">
  <?php foreach ($posts as $post) : ?>
    <?php echo $this->render('posts/post', array('post' => $post)); ?>
  <?php endforeach; ?>
</div>
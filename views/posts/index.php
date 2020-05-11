<?php $this->setLayoutVar('title', 'タイムライン') ?>

<div class="posts-index-wrapper">
  <h2>タイムライン</h2>

  <?php foreach ($posts as $post): ?>
    <a href="<?php echo $base_url; ?>/performances/<?php echo $post['performance_id'] ?>"><h3><?php echo $post['performance_name']; ?></h3></a>
    <?php echo $this->render('posts/post', array('post' => $post)); ?>
  <?php endforeach; ?>
</div>
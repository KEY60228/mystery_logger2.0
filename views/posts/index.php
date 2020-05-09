<?php $this->setLayoutVar('title', 'タイムライン') ?>

<h2>タイムライン</h2>

<?php foreach ($posts as $post): ?>
  <h3><?php echo $post['performance_name']; ?></h3>
  <?php echo $this->render('posts/post', array('post' => $post)); ?>
<?php endforeach; ?>
<?php $this->setLayoutVar('title', 'タイムライン') ?>

<h2>タイムライン</h2>

<?php foreach ($posts as $post): ?>
  <?php echo $this->render('posts/post', array('post' => $post)); ?>
<?php endforeach; ?>
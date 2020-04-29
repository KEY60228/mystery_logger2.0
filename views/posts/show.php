<?php $this->setLayoutVar('title', $post['user_name']) ?>

<?php echo $this->render('posts/post', array('post' => $post)); ?>

<?php if($user['id'] === $post['user_id'])  : ?>
  <a href="<?php echo $base_url; ?>/posts/<?php echo $post['id']; ?>/edit">編集</a>
  <a href="<?php echo $base_url; ?>/posts/<?php echo $post['id']; ?>/destroy">削除</a>
<?php endif; ?>
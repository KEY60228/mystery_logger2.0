<?php $this->setLayoutVar('title', $post['name']) ?>

<?php echo $this->render('posts/post', array('post' => $post)); ?>

<?php if($user['id'] === $post['user_id'])  : ?>
  <a href="<?php echo $base_url; ?>/posts/<?php echo $post['id']; ?>/edit">編集</a>
  
  <form action="<?php echo $base_url; ?>/posts/<?php echo $post['id']; ?>/destroy" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
  </form>
  
  <a href="<?php echo $base_url; ?>/posts/<?php echo $post['id']; ?>/destroy">削除</a>
<?php endif; ?>
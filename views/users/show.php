<?php $this->setLayoutVar('title', $user['name']) ?>

<h2><?php echo $this->escape($user['name']); ?></h2>

<?php if (!is_null($following)) : ?>
  <?php if ($following) : ?>
    <p>フォローしています</p>
  <?php else : ?>
    <form action="<?php echo $base_url; ?>/follow" method="post">
      <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
      <input type="hidden" name="following_name" value="<?php echo $this->escape($user['name']) ?>">
      <input type="submit" value="フォローする">
    </form>
  <?php endif; ?>
<?php endif; ?>

<?php if (!is_null($editable)) : ?>
  <?php if ($editable) : ?>
    <p>
      <a href="<?php echo $base_url; ?>/users/edit">編集</a>
    </p>
  <?php endif; ?>
<?php endif; ?>

<div id="posts">
  <?php foreach ($posts as $post) : ?>
    <?php echo $this->render('posts/post', array('post' => $post)); ?>
  <?php endforeach; ?>
</div>
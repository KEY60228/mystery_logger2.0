<?php $this->setLayoutVar('title', '編集') ?>

<h2>投稿の編集</h2>

<form action="<?php echo $base_url; ?>/posts/<?php echo $post['id']?>/update" method="post">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

  <?php if (isset($errors) && count($errors) > 0) : ?>
    <?php echo $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>

  <textarea name="contents" rows="2" cols="60"><?php echo $this->escape($post['contents']); ?></textarea>

  <p>
    <input type="submit" value="保存">
  </p>
</form>

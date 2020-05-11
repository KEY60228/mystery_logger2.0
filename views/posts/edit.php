<?php $this->setLayoutVar('title', '編集') ?>

<div class="posts-edit-wrapper">
  <div class="posts-edit-container">
  <h2>投稿の編集</h2>
  
    <form action="<?php echo $base_url; ?>/posts/<?php echo $post['id']?>/update" method="post" class="posts-edit-form">
      <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
    
      <?php if (isset($errors) && count($errors) > 0) : ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
      <?php endif; ?>
    
      <h3><?php echo $performance['name'] ?></h3>
      <textarea name="contents" onKeyUp="countLength(value);"><?php echo $this->escape($post['contents']); ?></textarea>
      <p id="js-count-characters"><?php echo mb_strlen($this->escape($post['contents']), 'UTF-8') ?>文字</p>
    
      <input type="submit" value="保存" class="posts-edit-button">
    </form>
  </div>
</div>

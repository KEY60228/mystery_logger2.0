<div class="post"> 
  <h3><?php echo $post['performance_name']; ?></h3>
  <div class="post_content">
    <img src="/user_images/<?php echo $post['user_image']; ?>" alt="プロフィール画像">
    <a href="<?php echo $base_url; ?>/users/<?php echo $this->escape($post['user_id']); ?>">
      <?php echo $this->escape($post['user_name']) ?>
    </a>
    <?php echo $this->escape($post['contents']) ?>
  </div>
  <div>
    <a href="<?php echo $base_url; ?>/posts/<?php echo $this->escape($post['id']); ?>">
      <?php echo $this->escape($post['created_at']); ?> 
    </a>
  </div>
</div>
<div class="img-and-post">
  <img src="/user_images/<?php echo $post['user_image']; ?>" alt="プロフィール画像">

  <div class="post-content">
      <a href="<?php echo $base_url; ?>/users/<?php echo $this->escape($post['user_id']); ?>">
        <?php echo $this->escape($post['user_name']) ?>
      </a>
      <a href="<?php echo $base_url; ?>/posts/<?php echo $this->escape($post['id']); ?>">
        <?php echo $this->escape($post['created_at']); ?> 
      </a>
    <p><?php echo $this->escape($post['contents']) ?></p>
  </div>
</div>

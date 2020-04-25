<div class="post">
  <div class="post_content">
    <a href="<?php echo $base_url; ?>/users/<?php echo $this->escape($post['user_id']); ?>">
      <?php echo $this->escape($post['name']) ?>
    </a>
    <?php echo $this->escape($post['contents']) ?>
  </div>
  <div>
    <a href="<?php echo $base_url; ?>/posts/<?php echo $this->escape($post['id']); ?>">
      <?php echo $this->escape($post['created_at']); ?> 
    </a>
  </div>
</div>
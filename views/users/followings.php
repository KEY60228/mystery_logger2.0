<?php $this->setLayoutVar('title', 'フォロー中'); ?>

<div class="follows-wrapper">
  <?php foreach ($followings as $following): ?>
    <div class="follows-container">
      <img src="/user_images/<?php echo $following['image_name']; ?>">
      <a href="<?php echo $base_url; ?>/users/<?php echo $following['id']; ?>"><?php echo $following['name']; ?></a>
    </div>
  <?php endforeach; ?>
</div>
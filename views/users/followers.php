<?php $this->setLayoutVar('title', 'フォロワー'); ?>

<div class="follows-wrapper">  
  <?php foreach ($followers as $follower): ?>
    <div class="follows-container">
      <img src="/user_images/<?php echo $follower['image_name']; ?>">
      <a href="<?php echo $base_url; ?>/users/<?php echo $follower['id']; ?>"><?php echo $follower['name']; ?></a>
    </div>
  <?php endforeach; ?>
</div>
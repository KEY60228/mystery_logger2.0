<?php $this->setLayoutVar('title', 'フォロワー'); ?>

<?php foreach ($followers as $follower): ?>
  <img src="<?php echo $base_url; ?>/user_images/<?php echo $follower['image_name']; ?>">
  <a href="<?php echo $base_url; ?>/users/<?php echo $follower['id']; ?>"><?php echo $follower['name']; ?></a>
<?php endforeach; ?>
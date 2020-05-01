<?php $this->setLayoutVar('title', 'フォロー中'); ?>

<?php foreach ($followings as $following): ?>
  <img src="<?php echo $base_url; ?>/images/<?php echo $following['image_name']; ?>">
  <a href="<?php echo $base_url; ?>/users/<?php echo $following['id']; ?>"><?php echo $following['name']; ?></a>
<?php endforeach; ?>
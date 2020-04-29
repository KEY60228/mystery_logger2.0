<?php $this->setLayoutVar('title', 'フォロー中'); ?>

<?php foreach ($followings as $following): ?>
  <a href="<?php echo $base_url; ?>/users/<?php echo $following['id']; ?>"><?php echo $following['name']; ?></a>
<?php endforeach; ?>
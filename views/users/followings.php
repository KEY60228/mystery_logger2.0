<?php $this->setLayoutVar('title', 'フォロー中のユーザー'); ?>

<?php foreach ($followings as $following): ?>
  <a href="<?php echo $base_url; ?>/users/<?php echo $following['id']; ?>"><?php echo $following['name']; ?></a>
<?php endforeach; ?>
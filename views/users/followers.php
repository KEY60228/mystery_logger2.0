<?php $this->setLayoutVar('title', 'フォロワー'); ?>

<?php foreach ($followers as $follower): ?>
  <a href="<?php echo $base_url; ?>/users/<?php echo $follower['id']; ?>"><?php echo $follower['name']; ?></a>
<?php endforeach; ?>
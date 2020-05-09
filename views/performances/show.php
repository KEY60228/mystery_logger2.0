<?php $this->setLayoutVar('title', $performance['name']); ?>

<div class="performances-wrapper">
  <img src="/performance_images/<?php echo $this->escape($performance['image_name']) ?>" alt="公演画像">
  
  <h3><?php echo $this->escape($performance['name']); ?></h3>
  
  <div class="performance-done-wanna">
    <?php if ($done): ?>
      <form action="<?php echo $base_url; ?>/performances/undone" method="post">
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
        <input type="hidden" name="performance_id" value="<?php echo $performance['id']; ?>">
        <input type="submit" value="行ってなかった…">
      </form>
    <?php else: ?>
      <form action="<?php echo $base_url; ?>/performances/done" method="post">
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
        <input type="hidden" name="performance_id" value="<?php echo $performance['id']; ?>">
        <input type="submit" value="行った！">
      </form>
    <?php endif; ?>
    
    <?php if ($wanna): ?>
      <form action="<?php echo $base_url; ?>/performances/disinterested" method="post">
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
        <input type="hidden" name="performance_id" value="<?php echo $performance['id']; ?>">
        <input type="submit" value="やっぱ気にならない">
      </form>
    <?php else: ?>
      <form action="<?php echo $base_url; ?>/performances/interested" method="post">
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
        <input type="hidden" name="performance_id" value="<?php echo $performance['id']; ?>">
        <input type="submit" value="行きたい！">
      </form>
    <?php endif; ?>
  </div>
  
  <div>
    <?php foreach ($posts as $post) : ?>
      <?php echo $this->render('posts/post', array('post' => $post)); ?>
    <?php endforeach; ?>
  </div>
</div>
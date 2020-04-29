<div class="performance">
  <div class="performance_content">
    <img src="<?php echo $this->escape($post['image_name']) ?>" alt="公演画像">
    <a href="<?php echo $base_url; ?>/performances/<?php echo $this->escape($performance['id']); ?>">
      <?php echo $this->escape($performance['name']) ?>
    </a>
  </div>
</div>
<?php $this->setLayoutVar('title', '公演一覧'); ?>

<div class="performances-wrapper">
  <h2>公演一覧</h2>
  
  <div class="performances">
    <?php foreach ($performances as $performance) : ?>
      <?php echo $this->render('performances/performance', array('performance' => $performance)); ?>
    <?php endforeach; ?>
  </div>
</div>
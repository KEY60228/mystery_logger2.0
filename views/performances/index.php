<?php $this->setLayoutVar('title', '公演一覧'); ?>

<h2>公演一覧</h2>

<?php foreach ($performances as $performance) : ?>
  <?php echo $this->render('performances/performance', array('performance' => $performance)); ?>
<?php endforeach; ?>
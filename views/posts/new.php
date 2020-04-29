<?php $this->setLayoutVar('title', '新規投稿') ?>

<h2>新規投稿</h2>

<form action="<?php echo $base_url; ?>/posts/create" method="post">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

  <select name="performance">
    <?php foreach ($performances as $performance): ?>
      <option value="<?php echo $performance['id'] ?>"><?php echo $performance['name']; ?></option>
    <?php endforeach; ?>
  </select>

  <?php if (isset($errors) && count($errors) > 0) : ?>
    <?php echo $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>

  <textarea name="contents" rows="2" cols="60"><?php echo $this->escape($contents); ?></textarea>

  <p>
    <input type="submit" value="投稿">
  </p>
</form>


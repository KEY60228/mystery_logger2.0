<?php $this->setLayoutVar('title', '編集') ?>

<div class="users-edit-wrapper">
  <div class="users-edit-container">
    <h2>ユーザー情報の編集</h2>
    
    <form action="<?php echo $base_url; ?>/users/update" method="post" enctype="multipart/form-data" class="users-edit-form">
      <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
      
      <?php if (isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
      <?php endif; ?>

      <div class="users-edit-content">
        <p>ユーザー名</p>
        <input type="text" name="user_name" value="<?php echo $this->escape($user_name); ?>">
      </div>
      <div class="users-edit-img">
        <p>プロフィール画像 (jpeg, pngのみ)</p>
        <input type="file" name="profile_image">
      </div>
    
      <input type="submit" value="保存" class="users-edit-button">

    </form>
  </div>
</div>
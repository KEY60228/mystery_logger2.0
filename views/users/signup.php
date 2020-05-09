<?php $this->setLayoutVar('title', '新規ユーザー登録') ?>

<div class="before-login-wrapper">
  <div class="before-login-container">
    <h2 class="before-login-subtitle">新規ユーザー登録</h2>
    
    <form action="<?php echo $base_url; ?>/users/register" method="post" enctype="multipart/form-data" class="before-login-form">
      <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
      
      <div class="before-login-client-input">
        
      <?php if (isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
      <?php endif; ?>
    
        <div class="before-login-client-content">
          <p>ユーザー名(必須)</p>
          <input type="text" name="user_name" value="<?php echo $this->escape($user_name); ?>">
        </div>
        <div class="before-login-client-content">
          <p>メールアドレス(必須)</p>
          <input type="text" name="email" value="<?php echo $this->escape($email); ?>">
        </div>
        <div class="before-login-client-content">
          <p>パスワード(必須)</p>
          <input type="password" name="password" value="<?php echo $this->escape($password); ?>">
        </div>
        <div class="before-login-client-img">
          <p>プロフィール画像 (jpeg, pngのみ)</p>
          <input type="file" name="profile_image">
        </div>
        <input type="submit" value="新規登録" class="before-login-button">
      </div>
      
      
    </form>
  </div>
</div>
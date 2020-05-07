<?php $this->setLayoutVar('title', 'ログイン'); ?>

<div class="background-img">
  <div class="before-login-container">
    <h2 class="before-login-subtitle">ログイン</h2>

    <form action="<?php echo $base_url; ?>/users/authenticate" method="post" class="before-login-form">
      <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
      
      <div class="before-login-client-input">
        
      <?php if (isset($errors) && count($errors) > 0) : ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
      <?php endif; ?>

        <div class="before-login-client-content">
          <p>メールアドレス</p>
          <input type="text" name="email" value="<?php echo $this->escape($email); ?>">
        </div>
        <div class="before-login-client-content">
          <p>パスワード</p>
          <input type="password" name="password" value="<?php echo $this->escape($password); ?>">
        </div>
        <input type="submit" value="ログイン" class="before-login-button">
      </div>

    </form>
  </div>
</div>
<?php $this->setLayoutVar('title', 'ログイン'); ?>

<h2>ログイン</h2>

<form action="<?php echo $base_url; ?>/users/authenticate" method="post">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
  
  <?php if (isset($errors) && count($errors) > 0) : ?>
    <?php echo $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>

  <table>
    <tbody>
      <tr>
        <th>メールアドレス</th>
        <td>
          <input type="text" name="email" value="<?php echo $this->escape($email); ?>">
        </td>
      </tr>
      <tr>
        <th>パスワード</th>
        <td>
          <input type="password" name="password" value="<?php echo $this->escape($password); ?>">
        </td>
      </tr>
    </tbody>
  </table>

  <p>
    <input type="submit" value="ログイン">
  </p>
</form>
<?php $this->setLayoutVar('title', '新規ユーザー登録') ?>

<h2>新規ユーザー登録</h2>

<form action="<?php echo $base_url; ?>/users/register" method="post" enctype="multipart/form-data">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

  <?php if (isset($errors) && count($errors) > 0): ?>
    <?php echo $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>

  <table>
    <tbody>
      <tr>
        <th>ユーザー名(必須)</th>
        <td>
          <input type="text" name="user_name" value="<?php echo $this->escape($user_name); ?>">
        </td>
      </tr>
      <tr>
        <th>メールアドレス(必須)</th>
        <td>
          <input type="text" name="email" value="<?php echo $this->escape($email); ?>">
        </td>
      </tr>
      <tr>
        <th>パスワード(必須)</th>
        <td>
          <input type="password" name="password" value="<?php echo $this->escape($password); ?>">
        </td>
      </tr>
      <tr>
        <th>プロフィール画像</th>
        <td>
          <input type="file" name="profile_image">
        </td>
      </tr>
    </tbody>
  </table>

  <p>
    <input type="submit" value="新規登録">
  </p>
</form>
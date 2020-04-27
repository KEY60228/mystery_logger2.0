<?php $this->setLayoutVar('title', '編集') ?>

<h2>ユーザー情報の編集</h2>

<form action="<?php echo $base_url; ?>/users/update" method="post">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

  <?php if (isset($errors) && count($errors) > 0): ?>
    <?php echo $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>

<!-- ここから -->
  <table>
    <tbody>
      <tr>
        <th>ユーザー名</th>
        <td>
          <input type="text" name="user_name" value="<?php echo $this->escape($user_name); ?>">
        </td>
      </tr>
      <tr>
        <th>メールアドレス</th>
        <td>
          <input type="text" name="email" value="<?php echo $this->escape($email); ?>">
        </td>
      </tr>
    </tbody>
  </table>
<!-- ここまで、後程共通ビューファイルに分離したい -->

  <p>
    <input type="submit" value="保存">
  </p>
</form>
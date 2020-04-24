<!-- ViewクラスのsetLayoutVarメソッドで連想配列に[title]=>新規登録を設定 -->
<?php $this->setLayoutVar('title', '新規ユーザー登録') ?>

<h2>新規ユーザー登録</h2>

<form action="<?php echo $base_url; ?>/users/register" method="post">
  <!-- csrfトークンの埋め込み -->
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

  <table>
    <tbody>
      <tr>
        <th>ユーザー名(必須)</th>
        <td>
          <input type="text" name="user_name" value="">
        </td>
      </tr>
      <tr>
        <th>メールアドレス(必須)</th>
        <td>
          <input type="text" name="email" value="">
        </td>
      </tr>
      <tr>
        <th>パスワード(必須)</th>
        <td>
          <input type="password" name="password" value="">
        </td>
      </tr>
    </tbody>
  </table>

  <p>
    <input type="submit" value="新規登録">
  </p>
</form>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="/css/style.css">
  <title><?php if (isset($title)) {echo $this->escape($title) . ' - ';} ?>なぞログ</title>
</head>
<body>

  <header>
    <p>
      <?php if (!$session->isAuthenticated()) : ?>
        <a href="<?php echo $base_url; ?>/">なぞログ</a>
      <?php else : ?>
        <a href="<?php echo $base_url ;?>/posts">なぞログ</a>
      <?php endif; ?>
    </p>
    <nav>
      <ul>
        <?php if ($session->isAuthenticated()) : ?>
          <?php $user = $session->get('user'); ?>
          <li>
            <a href="<?php echo $base_url; ?>/users/<?php echo $user['id']; ?>"><?php echo $user['name'] ?></a>
          </li>
          <li>
            <a href="<?php echo $base_url; ?>/performances">公演一覧</a>
          </li>
          <li>
            <a href="<?php echo $base_url; ?>/posts/new">新規投稿</a>
          </li>
          <li>
            <a href="<?php echo $base_url; ?>/users/signout">ログアウト</a>
          </li>
        <?php else: ?>
          <li>
            <a href="<?php echo $base_url; ?>/users/signin">ログイン</a>
          </li>
          <li>
            <a href="<?php echo $base_url; ?>/users/signup">新規登録</a>
          </li>
          <li>
            <a href="<?php echo $base_url; ?>/about">なぞログ #とは</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <main>
    <?php echo $_content; ?>
  <main>

  <footer>
    <p> - なぞログ - </p>
    <p>Copyright <a href="https://twitter.com/tatsuya_gucci" target=”_blank”>@tatsuya_gucci</a></p>
  </footer>

</body>
</html>
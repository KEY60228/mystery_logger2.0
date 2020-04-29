<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?php if (isset($title)) {echo $this->escape($title) . ' - ';} ?>なぞログ</title>
</head>
<body>
  <header>
    <h1 class="header-logo">
      <?php if (!$session->isAuthenticated()) : ?>
        <a href="<?php echo $base_url; ?>/">なぞログ</a>
      <?php else : ?>
        <a href="<?php echo $base_url ;?>/posts">なぞログ</a>
      <?php endif; ?>
    </h1>
    <ul class="header-menus">
      <?php if ($session->isAuthenticated()) : ?>
        <li>
          <!-- もっと良い書き方あるだろうけどとりあえずね… -->
          <a href="<?php echo $base_url; ?>/users/<?php $user = $session->get('user'); echo $user['id']; ?>"><?php echo $user['name'] ?></a>
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
  </header>

  <main>
    <?php echo $_content; ?>
  <main>

</body>
</html>
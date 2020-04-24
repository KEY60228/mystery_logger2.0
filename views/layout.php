<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?php if (isset($title)) {echo $this->escape($title) . ' - ';} ?>Mystery Logger2</title>
</head>
<body>
  <header>
    <h1 class="header-logo">
      <?php //if () : ?>
        <a href="<?php echo $base_url; ?>/">なぞログ</a>
      <?php //else : ?>
        <!-- <a href="<?php //echo ;?>" -->
      <?php //endif; ?>
    </h1>
    <ul class="header-menus">
      <?php //if () : ?>
        <!-- <li>
          <a href="">公演一覧</a>
        </li>
        <li>
          <a href="">新規投稿</a>
        </li>
        <li>
          <a href="">ログアウト</a>
        </li> -->
      <?php //else; ?>
        <li>
          <a href="">ログイン</a>
        </li>
        <li>
          <a href="<?php echo $base_url; ?>/users/signup">新規登録</a>
        </li>
        <li>
          <a href="<?php echo $base_url; ?>/about">なぞログ #とは</a>
        </li>
      <?php //endif; ?>
    </ul>
  </header>

  <main>
    <?php echo $_content; ?>
  <main>

</body>
</html>
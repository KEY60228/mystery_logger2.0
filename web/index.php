<?php

/**
 * 全てのリクエストはこのファイルにアクセスする(フロントコントローラー)
 */

require '../bootstrap.php';
require '../MysteryLogger2Application.php';

$app = new MysteryLogger2Application(false);
$app->run();
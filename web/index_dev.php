<?php

/**
 * 開発用フロントコントローラー
 * デバッグモードが有効になっている
 */

require '../bootstrap.php';
require '../MysteryLogger2Application.php';

$app = new MysteryLogger2Application(true);
$app->run();
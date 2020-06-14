<?php

/**
 * 例外の定義
 * 
 * なぞログ用に追加した例外処理
 * 権限がないにも関わらずアクセスを試みた時の例外 (ex. 投稿内容の編集 等)
 * 今のところPostControllerにしか使えない
 */
class NoRightActionException extends Exception {

}
<?php

class Session {
  protected static $sessionStarted = false;
  protected static $sessionIdRegenerated = false;

  /**
   * セッションが開始されていなければセッションを開始し、
   * $sessionStartedプロパティをtrueにするコンストラクタ
   */
  public function __construct() {
    if (!self::$sessionStarted) {
      session_start();
      self::$sessionStarted = true;
    }
  }

  /**
   * 識別子と内容を受け取り、セッションに設定する
   */
  public function set($name, $value) {
    $_SESSION[$name] = $value;
  }

  /**
   * 識別子$nameを受け取り、対応する内容を返す
   * 対応する識別子が設定されていない場合、$defaultの値を返す
   */
  public function get($name, $default = null) {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return $default;
  }

  /**
   * 識別子$nameのセッションを削除する
   */
  public function remove($name) {
    unset($_SESSION[$name]);
  }

  /**
   * セッションをリセットする
   */
  public function clear() {
    $_SESSION = array();
  }

  /**
   * セッションIDを再発行する
   */
  public function regenerate($destroy = true) {
    if (!self::$sessionIdRegenerated) {
      session_regenerate_id($destroy);
      self::$sessionIdRegenerated = true;
    }
  }

  /**
   * $_SESSION['_authenticated']に値を設定し、セッションIDを再発行する
   * ログイン状態にする機能
   */
  public function setAuthenticated($bool) {
    $this->set('_authenticated', (bool)$bool);
    $this->regenerate();
  }

  /**
   * $_SESSION['_authenticated']に値が入っている場合は$_SESSION['name']を返し、
   * 入っていない場合はfalseを返す
   * ログイン状態か否かを確認する機能
   */
  public function isAuthenticated() {
    return $this->get('_authenticated', false);
  }
}
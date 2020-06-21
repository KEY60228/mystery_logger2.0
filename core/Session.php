<?php

/**
 * セッション管理クラス Session
 */
class Session {
  protected static $sessionStarted = false;
  protected static $sessionIdRegenerated = false;

  /**
   * セッションを開始するコンストラクタ
   * 
   * セッションが開始されていなければセッションを開始し、
   * $sessionStartedプロパティをtrueにする
   */
  public function __construct() {
    if (!self::$sessionStarted) {
      session_start();
      self::$sessionStarted = true;
    }
  }

  /**
   * 識別子と内容を受け取り、セッションに値を設定するメソッド
   * 
   * @param string $name
   * @param mixed $value
   */
  public function set($name, $value) {
    $_SESSION[$name] = $value;
  }

  /**
   * セッションから値を取得するメソッド
   * 
   * 識別子$nameを受け取り、対応する内容を返す
   * 対応する識別子が設定されていない場合、$defaultの値を返す
   * 
   * @param string $name
   * @param mixed $default
   * @return mixed
   */
  public function get($name, $default = null) {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return $default;
  }

  /**
   * 識別子$nameのセッションを削除するメソッド
   * 
   * @param string $name
   */
  public function remove($name) {
    unset($_SESSION[$name]);
  }

  /**
   * セッションをリセットするメソッド
   */
  public function clear() {
    $_SESSION = array();
  }

  /**
   * セッションIDを再発行するメソッド
   * 
   * @param boolean $destroy
   */
  public function regenerate($destroy = true) {
    if (!self::$sessionIdRegenerated) {
      session_regenerate_id($destroy);
      self::$sessionIdRegenerated = true;
    }
  }

  /**
   * 認証状態を設定するメソッド
   * 
   * $_SESSION['_authenticated']に値を設定し、セッションIDを再発行する
   * ログイン状態にする機能
   * 
   * @param boolean $bool
   */
  public function setAuthenticated($bool) {
    $this->set('_authenticated', (bool)$bool);
    $this->regenerate();
  }

  /**
   * 認証状態をチェックするメソッド
   * 
   * $_SESSION['_authenticated']に値が入っている場合は$_SESSION['name']を返し、
   * 入っていない場合はfalseを返す
   * 
   * @return boolean
   */
  public function isAuthenticated() {
    return $this->get('_authenticated', false);
  }
}
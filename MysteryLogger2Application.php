<?php

class MysteryLogger2Application extends Application {
  protected $login_action = array('users', 'signin');

  /**
   * ルートディレクトリ(このファイルが置いてあるディレクトリ)を返す
   */
  public function getRootDir() {
    return dirname(__FILE__);
  }

  /**
   * ルーティング定義配列を返す
   */
  protected function registerRoutes() {
    // とりあえず動的アクションは保留
    return array(
      '/' => array('controller' => 'home', 'action' => 'top'),
      '/about' => array('controller' => 'home', 'action' => 'about'),
      '/posts/new' => array('controller' => 'posts', 'action' => 'new'),
      '/posts/create' => array('controller' => 'posts', 'action' => 'create'),
      '/posts/:id' => array('controller' => 'posts', 'action' => 'show'),
      '/users/signup' => array('controller' => 'users', 'action' => 'signup'),
      '/users/register' => array('controller' => 'users', 'action' => 'register'),
      '/users/signin' => array('controller' => 'users', 'action' => 'signin'),
      '/users/authenticate' => array('controller' => 'users', 'action' => 'authenticate'),
      '/users/signout' => array('controller' => 'users', 'action' => 'signout'),
      '/users/:id' => array('controller' => 'users', 'action' => 'show'),
    );
  }

  /**
   * アプリケーションの設定を行う
   * DbManagerクラスのconnectメソッドを用いてDBへの接続設定を行う(識別子はmaster)
   */
  protected function configure() {
    $this->db_manager->connect('master', array(
      'dsn' => 'pgsql:dbname=mystery_logger2',
      'user' => 'vagrant',
      'password' => 'vagrant',
    ));
  }
}
<?php

class MysteryLogger2Application extends Application {
  protected $login_action = array('');

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
      '/users' => array('controller' => 'users', 'action' =>'index'),
      '/users/:action' => array('controller' => 'users'),
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
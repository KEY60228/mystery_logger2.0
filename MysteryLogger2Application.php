<?php

class MysteryLogger2Application extends Application {
  protected $login_action = array('users', 'signin');
  protected $deny_action = array('users', 'show');

  /**
   * ルートディレクトリ(このファイルが置いてあるディレクトリ)を返す
   */
  public function getRootDir() {
    return dirname(__FILE__);
  }

  /**
   * user_imageディレクトリ へのパスを返す
   */
  public function getUserImagesDir() {
    return $this->getRootDir() . '/web/user_images';
  }

  /**
   * performance_imageディレクトリ へのパスを返す
   */
  public function getPerformanceImagesDir() {
    return $this->getRootDir() . '/web/performance_images';
  }

  /**
   * ルーティング定義配列を返す
   */
  protected function registerRoutes() {
    // とりあえず動的アクションは保留
    return array(
      '/' => array('controller' => 'home', 'action' => 'top'),
      '/about' => array('controller' => 'home', 'action' => 'about'),

      '/posts' => array('controller' => 'posts', 'action' => 'index'),
      '/posts/new' => array('controller' => 'posts', 'action' => 'new'),
      '/posts/create' => array('controller' => 'posts', 'action' => 'create'),
      '/posts/:id' => array('controller' => 'posts', 'action' => 'show'),
      '/posts/:id/edit' => array('controller' => 'posts', 'action' => 'edit'),
      '/posts/:id/update' => array('controller' => 'posts', 'action' => 'update'),
      '/posts/:id/destroy' => array('controller' => 'posts', 'action' => 'destroy'),

      '/users/signup' => array('controller' => 'users', 'action' => 'signup'),
      '/users/register' => array('controller' => 'users', 'action' => 'register'),
      '/users/signin' => array('controller' => 'users', 'action' => 'signin'),
      '/users/authenticate' => array('controller' => 'users', 'action' => 'authenticate'),
      '/users/signout' => array('controller' => 'users', 'action' => 'signout'),
      '/users/edit' => array('controller' => 'users', 'action' => 'edit'),
      '/users/update' => array('controller' => 'users', 'action' => 'update'),
      '/users/:id' => array('controller' => 'users', 'action' => 'show'),
      '/users/:id/followings' => array('controller' => 'users', 'action' => 'followings'),
      '/users/:id/followers' => array('controller' => 'users', 'action' => 'followers'),
      '/users/:id/done' => array('controller' => 'users', 'action' => 'done'),
      '/users/:id/wanna' => array('controller' => 'users', 'action' => 'wanna'),
      '/follow' => array('controller' => 'users', 'action' => 'follow'),
      '/unfollow' => array('controller' => 'users', 'action' => 'unfollow'),

      '/performances' => array('controller' => 'performances', 'action' => 'index'),
      '/performances/done' => array('controller' => 'performances', 'action' => 'done'),
      '/performances/undone' => array('controller' => 'performances', 'action' => 'undone'),
      '/performances/interested' => array('controller' => 'performances', 'action' => 'interested'),
      '/performances/disinterested' => array('controller' => 'performances', 'action' => 'disinterested'),
      '/performances/:id' => array('controller' => 'performances', 'action' => 'show'),
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
<?php

class DbManager {
  protected $connections = array();
  protected $repository_connection_map = array();
  protected $repositories = array();

  /**
   * 識別子$nameとPDOに渡す情報$paramsを受け取り、データベースとの接続を行う
   */
  public function connect($name, $params) {
    $params = array_merge(array(
      'dsn' => null,
      'user' => '',
      'password' => '',
      'options' => array(),
    ), $params);

    $con = new PDO(
      $params['dsn'],
      $params['user'],
      $params['password'],
      $params['options']
    );

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->connections[$name] = $con;
  }

  /**
   * 識別子$nameを受け取り、対応するデータベースとの接続(PDOインスタンス)を返す
   * $nameの指定がない場合は最初に作成したPDOインスタンスを返す
   */
  public function getConnection($name = null) {
    if(is_null($name)) {
      return current($this->connections);
    }
    return $this->connections[$name];
  }

  /**
   * Repositoryの名前$repository_nameと接続の識別子$nameを受け取り、
   * Repositoryごとの接続情報を設定する
   */
  public function setRepositoryConnectionMap($repository_name, $name) {
    $this->repository_connection_map[$repository_name] = $name;
  }

  /**
   * Repositoryの名前を受け取り、対応する接続(PDOインスタンス)を返す
   */
  public function getConnectionForRepository($repository_name) {
    if (isset($this->repository_connection_map[$repository_name])) {
      $name = $this->repository_connection_map[$repository_name];
      $con = $this->getConnection($name);
    } else {
      $con = $this->getConnection();
    }
    return $con;
  }

  /**
   * Repositoryの名前を受け取り、対応する接続(Repositoryインスタンス)を返す
   */
  public function get($repository_name) {
    if (!isset($this->repositories[$repository_name])) {
      $repository_class = $repository_name . 'Repository';
      $con = $this->getConnectionForRepository($repository_name);
      $repository = new $repository_class($con);
      $this->repositories[$repository_name] = $repository;
    }
    return $this->repositories[$repository_name];
  }

  /**
   * Repositoryとデータベースの接続を破棄するデストラクタ
   */
  public function __destruct() {
    foreach ($this->repositories as $repository) {
      unset($repository);
    }

    foreach ($this->connections as $con) {
      unset($con);
    }
  }
}
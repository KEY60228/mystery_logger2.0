<?php

/**
 * データベース制御クラス DbManager
 */
class DbManager {
  protected $connections = array();
  protected $repository_connection_map = array();
  protected $repositories = array();

  /**
   * データベースへの接続メソッド
   * 
   * @param string $name データベース識別子
   * @param array $params PDOに渡す情報
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
   * データベースへのコネクション取得メソッド
   * 
   * 識別子$nameを受け取り、対応するデータベースとの接続(を返す
   * $nameの指定がない場合は最初に作成したPDOインスタンスを返す
   * 
   * @param string $name データベース識別子
   * @return PDO
   */
  public function getConnection($name = null) {
    if(is_null($name)) {
      return current($this->connections);
    }
    return $this->connections[$name];
  }

  /**
   * リポジトリマッピングメソッド
   * 
   * Repositoryの名前$と接続の識別子$nameを受け取り、
   * Repositoryごとの接続情報を設定する
   * 
   * @param string $repository_name リポジトリ名
   * @param string $name DB接続識別子
   */
  public function setRepositoryConnectionMap($repository_name, $name) {
    $this->repository_connection_map[$repository_name] = $name;
  }

  /**
   * 指定されたリポジトリに対応する接続を取得するメソッド
   * 
   * @param string $repository_name
   * @return PDO
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
   * 指定されたリポジトリインスタンスを取得するメソッド
   * 
   * @param string $repository_name
   * @return DbRepository
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
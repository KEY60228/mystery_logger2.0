<?php

/**
 * テーブル管理クラス DbRepository
 */
class DbRepository {
  protected $con;

  /**
   * setConnection()を呼び出すコンストラクタ
   * 
   * @param PDO $con
   */
  public function __construct($con) {
    $this->setConnection($con);
  }

  /**
   * 接続情報を$conに格納する
   * 
   * @param PDO $con
   */
  public function setConnection($con) {
    $this->con = $con;
  }

  /**
   * SQL文を実行するメソッド
   * 
   * preparedかつ動的パラメータ(a.k.a. バインドパラメータ、プレースホルダー)が
   * 補完されたSQL文をデータベースに実行する
   * 
   * @param string $sql 実行するSQL文
   * @param array $params 動的パラメーター
   * @return PDOStatement $stmt
   */
  public function execute($sql, $params = array()) {
    $stmt = $this->con->prepare($sql);
    $stmt->execute($params);
    return $stmt;
  }

  /**
   * SQL文を実行し、結果を1行のみ取得するメソッド
   * 
   * @param string $sql 
   * @param array $params
   * @return array
   */
  public function fetch($sql, $params = array()) {
    return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * SQL文を実行し、結果を全て取得するメソッド
   * 
   * @param string $sql
   * @param array $params
   * @return array
   */
  public function fetchAll($sql, $params = array()) {
    return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  }
}
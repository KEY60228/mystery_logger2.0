<?php

class DbRepository {
  protected $con;

  /**
   * 接続情報(PDOインスタンス)を受け取り、DbRepositoryクラスのsetConnectionメソッドを呼び出すコンストラクタ
   */
  public function __construct($con) {
    $this->setConnection($con);
  }

  /**
   * 接続情報(PDOインスタンス)を受け取り、$conに格納する
   */
  public function setConnection($con) {
    $this->con = $con;
  }

  /**
   * SQL文と動的パラメーター(バインドパラメーター/プレースホルダー)を受け取り、
   * preparedかつ動的パラメーターが補完されたSQL文をデータベースに実行する
   * また、PDOStatementインスタンスを返す
   */
  public function execute($sql, $params = array()) {
    $stmt = $this->con->prepare($sql);
    $stmt->execute($params);
    return $stmt;
  }

  /**
   * SQL文と動的パラメーターを受け取り、DbReposirotyクラスのexecuteメソッドでSQL文を実行した後、
   * 帰ってきた値を連想配列で取得する (1行のみ)
   */
  public function fetch($sql, $params = array()) {
    return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * SQL文と動的パラメーターを受け取り、DbReposirotyクラスのexecuteメソッドでSQL文を実行した後、
   * 帰ってきた値を連想配列で取得する (複数行)
   */
  public function fetchAll($sql, $params = array()) {
    return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  }
}
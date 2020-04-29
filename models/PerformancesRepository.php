<?php

class PerformancesRepository extends DbRepository {
  /**
   * Performancesテーブルから全て抽出して返す
   */
  public function fetchAllPerformances() {
    $sql = "SELECT * FROM performances";
    return $this->fetchAll($sql);
  }

  /**
   * 公演IDを受け取り、DBから一致する公演情報を受け取る
   */
  public function fetchByPerformanceId($performance_id) {
    $sql = "SELECT performances.* FROM performances LEFT JOIN posts ON performances.id = posts.performance_id WHERE performances.id = :performance_id";
    
    return $this->fetch($sql, array(
      ':performance_id' => $performance_id,
    ));
  }

  /**
   * ユーザーIDを受け取り、そのユーザーが「行った」した公演情報を返す
   */
  public function fetchAllDonesByUserId($user_id) {
    $sql = "SELECT performances.* FROM performances LEFT JOIN dones ON performances.id = dones.performance_id WHERE user_id = :user_id";
    return $this->fetchAll($sql, array(
      ':user_id' => $user_id,
    ));
  }

  /**
   * ユーザーIDを受け取り、そのユーザーが「行きたい」した公演情報を返す
   */
  public function fetchAllWannasByUserId($user_id) {
    $sql = "SELECT performances.* FROM performances LEFT JOIN wannas ON performances.id = wannas.performance_id WHERE user_id = :user_id";
    return $this->fetchAll($sql, array(
      ':user_id' => $user_id,
    ));
  }
}
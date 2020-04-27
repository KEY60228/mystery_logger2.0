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
   * 公演IDを受け取り、DBから一致する公演情報と投稿情報を受け取る
   */
  public function fetchByPerformanceId($performance_id) {
    $sql = "SELECT * FROM performances LEFT JOIN posts ON performances.id = posts.performance_id WHERE performance_id = :performance_id";
    
    return $this->fetch($sql, array(
      ':performance_id' => $performance_id,
    ));
  }
}
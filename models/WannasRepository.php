<?php

/** Wannasテーブルの管理クラス WannasRepository */
class WannasRepository extends DbRepository {
  /**
   * 行きたい！にするメソッド
   * 
   * @param int $user_id
   * @param int $performance_id
   */
  public function insert($user_id, $performance_id) {
    $now = new DateTime();
    $sql = "INSERT INTO wannas (user_id, performance_id, wanted_at) VALUES (:user_id, :performance_id, :wanted_at)";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':performance_id' => $performance_id,
      ':wanted_at' => $now->format('Y-m-d H:i:s'),
    ));
  }

  /**
   * 行きたい！を取り消すメソッド
   * 
   * @param $user_id
   * @param $performance_id
   */
  public function delete($user_id, $performance_id) {
    $sql = "DELETE FROM wannas WHERE user_id = :user_id AND performance_id = :performance_id";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      'performance_id' => $performance_id
    ));
  }
  
  /**
   * 行きたい！の判定用メソッド
   * 
   * @param int $user_id
   * @param int $performance_id
   * @return boolean
   */
  public function isWanna($user_id, $performance_id) {
    $sql = "SELECT COUNT(user_id) as count FROM wannas WHERE user_id = :user_id AND performance_id = :performance_id";
    $row = $this->fetch($sql, array(
      ':user_id' => $user_id,
      'performance_id' => $performance_id
    ));

    if ($row['count'] != 0) {
      return true;
    }
    return false;
  }
}
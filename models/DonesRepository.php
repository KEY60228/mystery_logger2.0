<?php

/**
 * Donesテーブル管理クラス DonesRepository
 */
class DonesRepository extends DbRepository {
  /**
   * 行った！にするメソッド
   * 
   * @param int $user_id
   * @param int $performance_id
   */
  public function insert($user_id, $performance_id) {
    $now = new DateTime();
    $sql = "INSERT INTO dones (user_id, performance_id, done_at) VALUES (:user_id, :performance_id, :done_at)";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':performance_id' => $performance_id,
      ':done_at' => $now->format('Y-m-d H:i:s'),
    ));
  }

  /**
   * 行った！を取り消すメソッド
   * 
   * @param $user_id
   * @param $performance_id
   */
  public function delete($user_id, $performance_id) {
    $sql = "DELETE FROM dones WHERE user_id = :user_id AND performance_id = :performance_id";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      'performance_id' => $performance_id
    ));
  }

  /**
   * 行った！の判定用メソッド
   * 
   * @param int $user_id
   * @param int $performance_id
   * @return boolean
   */
  public function isDone($user_id, $performance_id) {
    $sql = "SELECT COUNT(user_id) as count FROM dones WHERE user_id = :user_id AND performance_id = :performance_id";
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
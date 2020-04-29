<?php

class DonesRepository extends DbRepository {
  /**
   * ユーザーIDと公演IDを受け取り、DBにinsert文を実行する
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
   * ユーザーIDと公演IDを受け取り、DBにdelete文を実行する
   */
  public function delete($user_id, $performance_id) {
    $sql = "DELETE FROM dones WHERE user_id = :user_id AND performance_id = :performance_id";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      'performance_id' => $performance_id
    ));
  }

  /**
   * ユーザーIDと公演IDを受け取り、Doneしているか否か返す
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
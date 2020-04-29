<?php

class FollowingsRepository extends DbRepository {
  /**
   * フォロー元のユーザーIDとフォロー先のユーザーIDを受け取り、DBにinsert文を実行する
   */
  public function insert($user_id, $following_id) {
    $sql = "INSERT INTO followings (user_id, following_id) VALUES (:user_id, :following_id)";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':following_id' => $following_id,
    ));
  }

  /**
   * フォロー元のユーザーIDとフォロー先のユーザーIDを受け取り、DBにdelete文を実行する
   */
  public function delete($user_id, $following_id) {
    $sql = "DELETE FROM followings WHERE user_id = :user_id AND following_id = :following_id";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':following_id' => $following_id,
    ));
  }

  /**
   * user_idとfollowing_idを受け取り、DBにセレクト文を実行、
   * 一致するuser_idとfollowing_idが0でなければtrue、それ以外ならばfalseを返す
   * (MySQLなら文字列で、pgsqlなら整数型で返ってくるようなので型を含めない比較にした)
   */
  public function isFollowing($user_id, $following_id) {
    $sql = "SELECT COUNT(user_id) as count FROM followings WHERE user_id = :user_id AND following_id = :following_id";
    $row = $this->fetch($sql, array(
      ':user_id' => $user_id,
      ':following_id' => $following_id
    ));

    if ($row['count'] != 0) {
      return true;
    }

    return false;
  }

  /**
   * ユーザーIDを受け取り、そのユーザーがフォローしてるユーザーの数を返す
   */
  public function CountFollowingsByUserId($user_id) {
    $sql = "SELECT COUNT(following_id) as count FROM followings WHERE user_id = :user_id";
    $row = $this->fetch($sql, array(
      ':user_id' => $user_id,
    ));
    return $row['count'];
  }
}
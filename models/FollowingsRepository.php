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
   * 
   */
  public function isFollowing($user)
}
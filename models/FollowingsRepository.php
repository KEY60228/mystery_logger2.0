<?php

/**
 * Followingsテーブルの管理クラス FollowingsRepository
 */
class FollowingsRepository extends DbRepository {
  /**
   * フォローするメソッド
   * 
   * フォロー元のIDとフォロー先のIDを受け取り、insert文を実行する
   * 
   * @param int $user_id
   * @param int $following_id
   */
  public function insert($user_id, $following_id) {
    $now = new DateTime();
    $sql = "INSERT INTO followings (user_id, following_id, following_at) VALUES (:user_id, :following_id, :following_at)";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':following_id' => $following_id,
      ':following_at' => $now->format('Y-m-d H:i:s')
    ));
  }

  /**
   * フォロー解除するメソッド
   * 
   * フォロー元のIDとフォロー先のIDを受け取り、delete文を実行する
   * 
   * @param int $user_id
   * @param int $following_id
   */
  public function delete($user_id, $following_id) {
    $sql = "DELETE FROM followings WHERE user_id = :user_id AND following_id = :following_id";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':following_id' => $following_id,
    ));
  }

  /**
   * フォローしているか確認するメソッド
   * 
   * user_idとfollowing_idを受け取り、DBにセレクト文を実行、
   * 一致するuser_idとfollowing_idが0でなければtrue、それ以外ならばfalseを返す
   * 
   * @param int $user_id
   * @param int $following_id
   * @return boolean
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
   * フォロー数を返すメソッド
   * 
   * ユーザーIDを受け取り、そのユーザーがフォローしてるユーザーの数を返す
   * 
   * @param int $user_id
   * @return int
   */
  public function CountFollowingsByUserId($user_id) {
    $sql = "SELECT COUNT(following_id) as count FROM followings WHERE user_id = :user_id";
    $row = $this->fetch($sql, array(
      ':user_id' => $user_id,
    ));
    return $row['count'];
  }
  
  /**
   * フォロワー数を返すメソッド
   * 
   * ユーザーIDを受け取り、そのユーザーのフォローされているユーザーの数を返す
   * 
   * @param int $user_id
   * @return int
   */
  public function CountFollowersByUserId($user_id) {
    $sql = "SELECT COUNT(following_id) as count FROM followings WHERE following_id = :user_id";
    $row = $this->fetch($sql, array(
      ':user_id' => $user_id,
    ));
    return $row['count'];
  }
}
<?php

/**
 * Postsテーブルの管理クラス PostsRepository
 */
class PostsRepository extends DbRepository {
  /**
   * ユーザーIDと入力した内容を受け取り、insert文を実行するメソッド
   * 
   * @param int $user_id
   * @param string $contents
   * @param int $performance_id
   */
  public function insert($user_id, $contents, $performance_id) {
    $now = new DateTime();
    $sql = "INSERT INTO posts (user_id, contents, performance_id, created_at) VALUES (:user_id, :contents, :performance_id, :created_at)";

    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':contents' => $contents,
      ':performance_id' => $performance_id,
      ':created_at' => $now->format('Y-m-d H:i:s'),
    ));
  }

  /**
   * タイムライン用抽出メソッド
   * 
   * posts, users, followingsテーブルを連結させた上で、
   * 該当のユーザーIDのユーザーの名前と投稿情報全てを抽出する
   * 
   * @param int $user_id
   * @return array
   */
  public function fetchAllPersonalArchivesByUserId($user_id) {
    $sql = "SELECT posts.*, users.name AS user_name, users.image_name AS user_image, performances.name AS performance_name FROM posts LEFT JOIN users ON posts.user_id = users.id LEFT JOIN followings ON followings.following_id = posts.user_id AND followings.user_id = :user_id LEFT JOIN performances ON performances.id = posts.performance_id WHERE followings.user_id = :user_id OR users.id = :user_id ORDER BY posts.created_at DESC";

    return $this->fetchAll($sql, array(':user_id' => $user_id));
  }

  /**
   * users/showアクション用メソッド
   * 
   * postsテーブルとusersテーブルを連結させた上で、
   * 該当のユーザーIDのユーザーの名前と投稿情報全てを抽出する
   * 
   * @param int $user_id
   * @return array
   */
  public function fetchAllByUserId($user_id) {
    $sql = "SELECT posts.*, users.name users.image_name, FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE users.id = :user_id ORDER BY posts.created_at DESC";

    return $this->fetchAll($sql, array(':user_id' => $user_id));
  }

  /**
   * posts/showアクション用メソッド
   * 
   * postsテーブルとusersテーブルを連結させた上で、
   * 該当の投稿IDのユーザーの名前と投稿情報全てを抽出する
   * 
   * @param int $id
   * @return array
   */
  public function fetchById($id) {
    $sql = "SELECT posts.*, users.name AS user_name, users.image_name AS user_image, performances.name AS performance_name, performances.image_name AS performance_image FROM posts LEFT JOIN users ON users.id = posts.user_id LEFT JOIN performances ON performances.id = posts.performance_id WHERE posts.id = :id";
    
    return $this->fetch($sql, array(
      ':id' => $id,
    ));
  }

  /**
   * performances/showアクション用メソッド
   * 
   * postsテーブルとperformancesテーブルを連結させた上で、
   * 該当の公演IDの公演情報と投稿情報全てを抽出する
   * 
   * @param int $performance_id
   * @return array
   */
  public function fetchAllByPerformanceId($performance_id) {
    $sql = "SELECT posts.*, users.name AS user_name, users.image_name AS user_image, performances.name AS performance_name FROM posts LEFT JOIN users ON users.id = posts.user_id LEFT JOIN performances ON posts.performance_id = performances.id WHERE performance_id = :performance_id ORDER BY posts.created_at DESC";

    return $this->fetchAll($sql, array(
      ':performance_id' => $performance_id,
    ));
  }

  /**
   * 投稿IDと更新内容を受け取り、update文を実行するメソッド
   * 
   * @param int $id
   * @param string $contents
   */
  public function update($id, $contents) {
    $now = new DateTime();
    $sql = "UPDATE posts SET contents = :contents, updated_at = :updated_at WHERE id = :id";

    $stmt = $this->execute($sql, array(
      ':contents' => $contents,
      ':updated_at' => $now->format('Y-m-d H:i:s'),
      ':id' => $id,
    ));
  }

  /**
   * 投稿IDを受け取り、DELETE文を実行するメソッド
   * 
   * @param int $id
   */
  public function delete($id) {
    $sql = "DELETE FROM posts WHERE id = :id";
    $stmt = $this->execute($sql, array(
      ':id' => $id,
    ));
  }
}
<?php

class PostsRepository extends DbRepository {
  /**
   * 該当のユーザーIDとクライアントが入力した内容を受け取り、DBにinsert文を実行する
   */
  public function insert($user_id, $contents) {
    $now = new DateTime();
    $sql = "INSERT INTO posts (user_id, contents, created_at) VALUES (:user_id, :contents, :created_at";

    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':contents' => $contents,
      ':created_at' => $now->format('Y-m-d H:i:s'),
    ));
  }

  /**
   * 該当のユーザーIDを受け取り、postsテーブルとusersテーブルを連結させた上で、
   * 該当のユーザーIDのユーザーの名前と投稿情報全てを抽出する
   */
  public function fetchAllPersonalArchivesByUserId($user_id) {
    $sql = "SELECT posts.*, users.name FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE users.id = :user_id ORDER BY posts.created_at DESC";

    return $this->fetchAll($sql, array(':user_id' => $user_id));
  }
}
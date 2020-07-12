<?php

/**
 * Usersテーブルの管理クラス UserRepository
 */
class UsersRepository extends DbRepository {
  /**
   * DBへINSERT文を実行する
   * 
   * ユーザー名、メールアドレス、パスワード、イメージ名を受け取り、
   * パスワードをハッシュ化した後にDBへINSERTする
   * 
   * @param string $user_name
   * @param string $email
   * @param string $password
   * @param string $image_name
   */
  public function insert($user_name, $email, $password, $image_name) {
    $password = $this->hashPassword($password);
    $now = new DateTime();
    $sql = "INSERT INTO users (name, email, password, image_name, created_at) VALUES (:user_name, :email, :password, :image_name, :created_at)";

    $stmt = $this->execute($sql, array(
      ':user_name' => $user_name,
      ':email' => $email,
      ':password' => $password,
      ':image_name' => $image_name,
      ':created_at' => $now->format('Y-m-d H:i:s'),
    ));
  }

  /**
   * パスワードをハッシュ化するメソッド
   * 
   * @param string $password
   * @return string
   */
  public function hashPassword($password) {
    return sha1($password . 'SecretKey');
  }

  /**
   * ユーザー名を受け取り、DBにSELECT文を実行、結果を返すメソッド
   * 
   * @param string $user_name
   * @return array
   */
  public function fetchByUserName($user_name) {
    $sql = "SELECT * FROM users WHERE name = :user_name";
    return $this->fetch($sql, array(':user_name' => $user_name));
  }
  
  /**
   * 動的パラメータ:idを受け取り、DBにSELECT文を実行、結果を返すメソッド
   * 
   * @param int $id
   * @return array
   */
  public function fetchByUserId($id) {
    $sql = "SELECT * FROM users WHERE id = :id";
    return $this->fetch($sql, array(':id' => $id));
  }

  /**
   * メールアドレスを受け取り、DBにSELECT文を実行、結果を返すメソッド
   * 
   * @param string $email
   * @return array
   */
  public function fetchByEmail($email) {
    $sql = "SELECT * FROM users WHERE email = :email";
    return $this->fetch($sql, array(':email' => $email));
  }

  /**
   * ユーザー名がユニークか調べるメソッド
   * 
   * ユーザー名を受け取り、DBに SELECT文を実行、
   * 同じユーザー名のidが0ならばtrue、それ以外ならばfalseを返す
   * 
   * @param string $user_name
   * @return boolean
   */
  public function isUniqueUserName($user_name) {
    $sql = "SELECT COUNT (id) as count FROM users WHERE name = :user_name";
    $row = $this->fetch($sql, array(':user_name' => $user_name));

    if ($row['count'] == 0) {
      return true;
    }

    return false;
  }

  /**
   * メールアドレスがユニークか調べるメソッド
   * 
   * メールアドレスを受け取り、DBにSELECT文を実行、
   * 同じメールアドレスのidが0ならばtrue、それ以外ならばfalseを返す
   * 
   * @param string $email
   * @return boolean
   */
  public function isUniqueMailAddress($email) {
    $sql = "SELECT COUNT (id) as count FROM users WHERE email = :email";
    $row = $this->fetch($sql, array(':email' => $email));

    if ($row['count'] == 0) {
      return true;
    }

    return false;
  }

  /**
   * ユーザーIDを受け取ってフォローしているユーザーの情報を受け取るメソッド
   * 
   * @param string $user_id
   * @return array
   */
  public function fetchAllFollowingsByUserId($user_id) {
    $sql = "SELECT users.* FROM users LEFT JOIN followings ON followings.following_id = users.id WHERE followings.user_id = :user_id ORDER BY followings.following_at DESC";
    return $this->fetchAll($sql, array(':user_id' => $user_id));
  }
  
  /**
   * ユーザーIDを受け取ってフォローされているユーザーの情報を受け取るメソッド
   * 
   * @param string $user_id
   * @return array
   */
  public function fetchAllFollowersByUserId($user_id) {
    $sql = "SELECT users.* FROM users LEFT JOIN followings ON followings.user_id = users.id WHERE followings.following_id = :user_id ORDER BY followings.following_at DESC";
    return $this->fetchAll($sql, array(':user_id' => $user_id));
  }

  /**
   * ユーザーID、ユーザー名、ファイルを受け取り、DBにupdate文を実行するメソッド
   * 
   * @param string $user_id
   * @param string $user_name
   * @param string $image_name
   */
  public function update($user_id, $user_name, $image_name) {
    $now = new DateTime();
    $sql = "UPDATE users SET name = :user_name, image_name = :image_name, updated_at = :updated_at WHERE id = :user_id";
    $stmt = $this->execute($sql, array(
      ':user_name' => $user_name,
      ':image_name' => $image_name,
      ':updated_at' => $now->format('Y-m-d H:i:s'),
      ':user_id' => $user_id,
    ));
  }
}
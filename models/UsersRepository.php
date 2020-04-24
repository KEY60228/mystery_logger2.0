<?php

class UsersRepository extends DbRepository {
  /**
   * クライアントが入力したユーザー名、メールアドレス、パスワードを受け取り、
   * パスワードをハッシュ化した後にDBにINSERT文を実行する
   * (何故$stmtに代入しているかは不明…)
   */
  public function insert($user_name, $email, $password) {
    $password = $this->hashPassword($password);
    $now = new DateTime();
    $sql = "INSERT INTO users (name, email, password, created_at) VALUES (:user_name, :email, :password, :created_at)";

    $stmt = $this->execute($sql, array(
      ':user_name' => $user_name,
      ':email' => $email,
      ':password' => $password,
      ':created_at' => $now->format('Y-m-d H:i:s'),
    ));
  }

  /**
   * クライアントが入力したパスワードを受け取り、ハッシュ化する
   */
  public function hashPassword($password) {
    return sha1($password . 'SecretKey');
  }

  /**
   * クライアントが入力したユーザー名を受け取り、
   * DBにSELECT分を実行、結果を返す
   */
  public function fetchByUserName($user_name) {
    $sql = "SELECT * FROM users WHERE name = :user_name";
    return $this->fetch($sql, array(':user_name' => $user_name));
  }

  /**
   * クライアントが入力したユーザー名を受け取り、
   * DBにセレクト文を実行、同じユーザー名のidが0ならばtrue、それ以外ならばfalseを返す
   * (MySQLなら文字列で、pgsqlなら整数型で返ってくるようなので型を含めない比較にした)
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
   * クライアントが入力したメールアドレスを受け取り、
   * DBにセレクト文を実行、同じメールアドレスのidが0ならばtrue、それ以外ならばfalseを返す
   * (MySQLなら文字列で、pgsqlなら整数型で返ってくるようなので型を含めない比較にした)
   */
  public function isUniqueMailAddress($email) {
    $sql = "SELECT COUNT (id) as count FROM users WHERE email = :email";
    $row = $this->fetch($sql, array(':email' => $email));

    if ($row['count'] == 0) {
      return true;
    }

    return false;
  }
}
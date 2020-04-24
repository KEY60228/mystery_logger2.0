<?php

class UsersController extends Controller {
  /**
   * CSRFトークンを発行し、ビューファイルに渡したものをレンダリングする
   */
  public function signupAction() {
    return $this->render(array(
      'user_name' => '',
      'email' => '',
      'password' => '',
      '_token' => $this->generateCsrfToken('users/signup'),
    ));
  }

  /**
   * HTTPメソッドがpostでなければ404ページに遷移させる
   * CSRFトークンの照合を行い、マッチしなければsignupページにリダイレクトさせる
   * クライアントが入力したユーザー名、メールアドレス、パスワードをチェックし、
   * エラーが0の場合はinsert文を実行、ログイン状態に(セッション開始)してでトップページにリダイレクト
   * それ以外の場合は入力値を渡してsignupページを再表示させる(リダイレクトではない)
   */
  public function registerAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if(!$this->checkCsrfToken('users/signup', $token)) {
      return $this->redirect('/users/signup');
    }

    $user_name = $this->request->getPost('user_name');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $errors = array();

    if (!strlen($user_name)) {
      $errors[] = 'ユーザー名を入力してください';
    } elseif (!preg_match('/^\w{3,20}$/', $user_name)) {
      // とりあえずね
      $errors[] = 'ユーザー名は半角英数ならびにアンダースコアで3〜20字で入力してください';
    } elseif (!$this->db_manager->get('Users')->isUniqueUserName($user_name)) {
      $errors[] = 'ユーザー名は既に使用されています';
    }
    
    if (!strlen($email)) {
      $errors[] = 'メールアドレスを入力してください';
    } elseif (!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $email)) {
      $errors[] = 'メールアドレスを正しく入力してください';
    } elseif (!$this->db_manager->get('Users')->isUniqueMailAddress($email)) {
      $errors[] = 'メールアドレスは既に使用されています';
    }

    if (!strlen($password)) {
      $errors[] = 'パスワードを入力してください';
    } elseif (!preg_match('/^\w{4,40}$/', $password)) {
      // とりあえずね
      $errors[] = 'パスワードは半角英数ならびにアンダースコアを4〜40字で入力してください';
    }

    if (count($errors) === 0) {
      $this->db_manager->get('Users')->insert($user_name, $email, $password);
      $this->session->setAuthenticated(true);
      $user = $this->db_manager->get('Users')->fetchByUserName($user_name);
      $this->session->set('user', $user);
      return $this->redirect('/');
    }

    return $this->render(array(
      'user_name' => $user_name,
      'email' => $email,
      'password' => $password,
      'errors' => $errors,
      '_token' => $this->generateCsrfToken('users/signup'),
    ), 'signup');
  }
}
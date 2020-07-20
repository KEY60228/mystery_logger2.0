<?php

/**
 * ユーザー関係のクラス UsersController
 */
class UsersController extends Controller {
  // ログインが必要なアクションを指定する
  protected $auth_actions = array('show', 'signout', 'follow', 'edit', 'update', 'unfollow', 'followings', 'followers');

  /**
   * CSRFトークンを発行し、ビューファイルに渡したものをレンダリングする
   * 
   * @return string
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
   * ユーザー登録アクション
   * 
   * HTTPメソッドがpostでなければ404ページに遷移させる
   * CSRFトークンの照合を行い、マッチしなければsignupページにリダイレクトさせる
   * クライアントが入力したユーザー名、メールアドレス、パスワードをチェックし、
   * エラーが0の場合はinsert文を実行、ログイン状態に(セッション開始)してでトップページにリダイレクト
   * それ以外の場合は入力値を渡してsignupページを再表示させる(リダイレクトではない)
   * 
   * @throws HttpNotFoundException | @return void|string
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

    $profile_image = $this->request->getFile('profile_image');
    if (strlen($profile_image['tmp_name'])) {
      $image_error = $profile_image['error'];
      $image_type = $this->request->getImageType($profile_image['tmp_name']);
    } else {
      $profile_image = '';
      $image_error = '';
      $image_type = '';
    }

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
    
    if (strlen($image_error) && $image_error != 'UPLOAD_ERR_OK') {
      $errors[] = 'アップロードエラーです';
    }

    if (strlen($image_type) && ($image_type !== 'jpeg' && $image_type !== 'png')) {
      $errors[] = 'jpegかpngファイルのみ可能です';
    }

    if (strlen($image_type)){
      $image_filename = sprintf('%s_%s.%s', time(), sha1(uniqid(mt_rand(), true)), $image_type);
      $save_path = $this->application->getUserImagesDir() . '/' . $image_filename;
    } else {
      $image_filename = "default.jpeg";
    }

    if (count($errors) === 0) {
      $upload = true;
      if (strlen($image_error) && $image_error == 'UPLOAD_ERR_OK') {
        $upload = move_uploaded_file($profile_image['tmp_name'], $save_path);
      }
      if ($upload === true) {
        $this->db_manager->get('Users')->insert($user_name, $email, $password, $image_filename);
        $this->session->setAuthenticated(true);
        $user = $this->db_manager->get('Users')->fetchByUserName($user_name);
        $this->session->set('user', $user);
        return $this->redirect('/users/' . $user['id']);  
      } else {
        $errors[] = '画像のアップロード中に何か問題が起きました。管理者に問合せください';
      }
    }
    
    return $this->render(array(
      'user_name' => $user_name,
      'email' => $email,
      'password' => $password,
      'errors' => $errors,
      '_token' => $this->generateCsrfToken('users/signup'),
    ), 'signup');
  }

  /**
   * ユーザー詳細ページの表示アクション
   * 
   * ルーティングマッチ配列を受け取り、ユーザーIDでユーザーの存在を確認した後、
   * postsテーブルから投稿情報とユーザー情報を抽出し、ページを表示する
   * ユーザーが存在しなかった場合は404ページに遷移させる
   * 
   * @throws HttpNotFoundException | @return string
   */
  public function showAction($params) {
    $user = $this->db_manager->get('Users')->fetchByUserId($params['id']);

    if (!$user) {
      $this->forward404();
    }

    $following = null;
    $editable = null;

    if ($this->session->isAuthenticated()) {
      $my = $this->session->get('user');
      if ($my['id'] !== $user['id']) {
        $following = $this->db_manager->get('Followings')->isFollowing($my['id'], $user['id']);
      } else {
        $editable = true;
      }
    }

    $followings = $this->db_manager->get('Followings')->CountFollowingsByUserId($params['id']);
    $followers = $this->db_manager->get('Followings')->CountFollowersByUserId($params['id']);

    $dones = $this->db_manager->get('Performances')->fetchAllDonesByUserId($user['id']);
    $wannas = $this->db_manager->get('Performances')->fetchAllWannasByUserId($user['id']);

    return $this->render(array(
      'user' => $user,
      'following' => $following,
      'editable' => $editable,
      'followings' => $followings,
      'followers' => $followers,
      'dones' => $dones,
      'wannas' => $wannas,
      '_token' => $this->generateCsrfToken('users/show'),
    ));
  }

  /**
   * ログインページの表示メソッド
   * 
   * ログインページを表示させる
   * 既にログイン済みの場合はリダイレクトさせる
   * 
   * @return void|string
   */
  public function signinAction() {
    if ($this->session->isAuthenticated()) {
      $user = $this->session->get('user');
      return $this->redirect('/users/' . $user['id']);
    }

    return $this->render(array(
      'email' => '',
      'password' => '',
      '_token' => $this->generateCsrfToken('users/signin'),
    ));
  }

  /**
   * ログイン認証アクション
   * 
   * 既にログイン済の場合はリダイレクトさせ、HTTPメソッドがPOST以外の場合は404ページに遷移させる
   * トークンの照合が正しくない場合もリダイレクトさせる
   * メールアドレスもパスワードも正しければ、ログイン状態にした上でユーザーぺーじに遷移させる
   * それ以外の場合は入力値を渡して再度ログインページを表示させる (リダイレクトではない)
   * 
   * @throws HttpNotFoundException | @return void|string
   */
  public function authenticateAction() {
    if ($this->session->isAuthenticated()) {
      // redirect先はとりあえずトップページで
      return $this->redirect('/');
    }

    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrftoken('users/signin', $token)) {
      return $this->redirect('/users/signin');
    }

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $errors = array();

    if (!strlen($email)) {
      $errors[] = 'メールアドレスを入力してください';
    }

    if (!strlen($password)) {
      $errors[] = 'パスワードを入力してください';
    }

    if (count($errors) === 0) {
      $users_repository = $this->db_manager->get('Users');
      $user = $users_repository->fetchByEmail($email);

      if (!$user || ($user['password'] !== $users_repository->hashPassword($password))) {
        $errors[] = 'メールアドレスかパスワードが不正です';
      } else {
        $this->session->setAuthenticated(true);
        $this->session->set('user', $user);

        return $this->redirect('/users/' . $user['id']);
      }
    }

    return $this->render(array(
      'email' => $email,
      'password' => $password,
      'errors' => $errors,
      '_token' => $this->generateCsrfToken('users/signin'),
    ), 'signin');
  }

  /**
   * ログアウトアクション
   * 
   * セッションをクリアし、ログインページへリダイレクトする
   * 
   * @return void
   */
  public function signoutAction() {
    $this->session->clear();
    $this->session->setAuthenticated(false);

    return $this->redirect('/users/signin');
  }

  /**
   * フォローを行うアクション
   * 
   * HTTPメソッドがPostでない、また、following_nameを受け取っていなかったら404に遷移
   * CSRFトークンも照合し、不正な場合はリダイレクトさせる
   * フォロー元のユーザーIDがフォロー先のユーザーIDと一致せず、既にフォローしていないユーザーだった場合、
   * Followingsテーブルにinsert文を実行し、フォロー先ユーザーの詳細ページにリダイレクトする
   * 
   * @throws HttpNotFoundException | @return void
   */
  public function followAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $following_name = $this->request->getPost('following_name');
    if (!$following_name) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrfToken('users/show', $token)) {
      return $this->redirect('/users/' . $following_name);
    }

    $follow_user = $this->db_manager->get('Users')->fetchByUserName($following_name);
    if (!$follow_user) {
      $this->forward404();
    }

    $user = $this->session->get('user');
    
    $followings_repository = $this->db_manager->get('Followings');
    if ($user['id'] !== $follow_user['id'] && !$followings_repository->isFollowing($user['id'], $follow_user['id'])) {
      $followings_repository->insert($user['id'], $follow_user['id']);
    }

    return $this->redirect('/users/' . $follow_user['id']);
  }

  /**
   * 編集ページを表示させるメソッド
   * 
   * @return string
   */
  public function editAction() {
    $user = $this->session->get('user');
    return $this->render(array(
      'user_name' => $user['name'],
      '_token' => $this->generateCsrfToken('users/edit'),
    ));
  }

  /**
   * ユーザー情報の更新を行うアクション
   * 
   * HTTPメソッドがPostでない場合は404に遷移させ、CSRFトークンが不正な場合はリダイレクトさせる
   * ユーザー名とメールアドレスのチェックを行い、エラーがなければDBにUPDATEを実行し、
   * 該当のユーザー詳細ページにリダイレクトさせる
   * エラーがある場合は再度editページを表示させる (リダイレクトではない)
   * 
   * @throws HttpNotFoundException | @return void|string
   */
  public function updateAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if(!$this->checkCsrfToken('users/edit', $token)) {
      return $this->redirect('/users/edit');
    }

    $my = $this->session->get('user');
    $user_name = $this->request->getPost('user_name');

    $profile_image = $this->request->getFile('profile_image');
    if (strlen($profile_image['tmp_name'])) {
      $image_error = $profile_image['error'];
      $image_type = $this->request->getImageType($profile_image['tmp_name']);
    } else {
      $profile_image = '';
      $image_error = '';
      $image_type = '';
    }

    $errors = array();

    if (!strlen($user_name)) {
      $errors[] = 'ユーザー名を入力してください';
    } elseif (!preg_match('/^\w{3,20}$/', $user_name)) {
      // とりあえずね
      $errors[] = 'ユーザー名は半角英数ならびにアンダースコアで3〜20字で入力してください';
    } elseif ($user_name !== $my['name'] && !$this->db_manager->get('Users')->isUniqueUserName($user_name)) {
      $errors[] = 'ユーザー名は既に使用されています';
    }

    if (strlen($image_error) && $image_error != 'UPLOAD_ERR_OK') {
      $errors[] = 'アップロードエラーです';
    }

    if (strlen($image_type) && ($image_type !== 'jpeg' && $image_type !== 'png')) {
      $errors[] = 'jpegかpngファイルのみ可能です';
    }

    if (strlen($image_type)){
      $image_filename = sprintf('%s_%s.%s', time(), sha1(uniqid(mt_rand(), true)), $image_type);
      $save_path = $this->application->getUserImagesDir() . '/' . $image_filename;
    } else {
      $image_filename = $my['image_name'];
    }

    if (count($errors) === 0) {
      $upload = true;
      if (strlen($image_error) && $image_error == 'UPLOAD_ERR_OK') {
        $upload = move_uploaded_file($profile_image['tmp_name'], $save_path);
      }
      if ($upload === true) {
        $this->db_manager->get('Users')->update($my['id'], $user_name, $image_filename);
        $user = $this->db_manager->get('Users')->fetchByUserName($user_name);
        $this->session->set('user', $user);
        return $this->redirect('/users/' . $user['id']);
      } else {
        $errors[] = '画像のアップロード中に何か問題が起きました。管理者に問合せください';
      }
    }

    return $this->render(array(
      'user_name' => $user_name,
      'errors' => $errors,
      '_token' => $this->generateCsrfToken('users/edit'),
    ), 'edit');
  }

  /**
   * フォロー解除するアクション
   * 
   * HTTPメソッドがPostでない、また、following_nameを受け取っていなかったら404に遷移
   * CSRFトークンも照合し、不正な場合はリダイレクトさせる
   * フォロー元のユーザーIDがフォロー先のユーザーIDと一致せず、既にフォローしているユーザーだった場合、
   * Followingsテーブルにdelete文を実行し、フォロー先ユーザーの詳細ページにリダイレクトする
   * 
   * @throws HttpNotFoundException | @return void
   */
  public function unfollowAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $following_name = $this->request->getPost('following_name');
    if (!$following_name) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrfToken('users/show', $token)) {
      return $this->redirect('/users/' . $following_name);
    }

    $follow_user = $this->db_manager->get('Users')->fetchByUserName($following_name);
    if (!$follow_user) {
      $this->forward404();
    }

    $user = $this->session->get('user');

    $followings_repository = $this->db_manager->get('Followings');
    if ($user['id'] !== $follow_user['id'] && $followings_repository->isFollowing($user['id'], $follow_user['id'])) {
      $followings_repository->delete($user['id'], $follow_user['id']);
    }

    return $this->redirect('/users/' . $follow_user['id']);
  }

  /**
   * フォローしているユーザー一覧を表示する
   * 
   * @return string
   */
  public function followingsAction($params) {
    $followings = $this->db_manager->get('Users')->fetchAllFollowingsByUserId($params['id']);

    return $this->render(array(
      'followings' => $followings,
    ));
  }
  
  /**
   * フォローしているユーザー一覧を表示する
   * 
   * @return string
   */
  public function followersAction($params) {
    $followers = $this->db_manager->get('Users')->fetchAllFollowersByUserId($params['id']);

    return $this->render(array(
      'followers' => $followers,
    ));
    
  }
}
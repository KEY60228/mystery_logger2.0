<?php

class PostsController extends Controller {
  // ログインが必要なアクションを指定する
  protected $auth_actions = array('new', 'create', 'show');

  /**
   * 新規投稿ページを返す
   * 新規投稿ページには前回記入したcontentsとtokenを渡す
   */
  public function newAction() {
    return $this->render(array(
      'contents' => '',
      '_token' =>$this->generateCsrfToken('posts/new'),
    ));
  }

  /**
   * 投稿アクション
   * HTTPメソッドがPost以外の場合は404ページに遷移させ、トークンの照合が不正な場合はリダイレクトする
   * 投稿に問題なければinsert文を実行しユーザー詳細ページにリダイレクトし、
   * 問題がある場合は各データを渡して再度表示させる (リダイレクトではない)
   */
  public function createAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrfToken('posts/new', $token)) {
      return $this->redirect('/posts/new');
    }

    $contents = $this->request->getPost('contents');

    $errors = array();

    if (!strlen($contents)) {
      $errors[] = '感想を入力してください';
    } elseif (mb_strlen($contents) > 200) {
      $errors[] = '感想は200字以内で入力してください';
    }

    if (count($errors) === 0) {
      $user = $this->session->get('user');
      $this->db_manager->get('Posts')->insert($user['id'], $contents);
      return $this->redirect('/users/' . $user['id']);
    }

    $user = $this->session->get('user');

    return $this->render(array(
      'errors' => $errors,
      'contents' => $contents,
      '_token' => $this->generateCsrfToken('posts/new'),
    ), 'new');
  }

  /**
   * ルーティングでマッチした配列を受け取り、投稿が存在するか確認した後、詳細ページを表示する
   * 投稿が存在しなかった場合は404ページに遷移させる
   */
  public function showAction($params) {
    $post = $this->db_manager->get('Posts')->fetchById($params['id']);

    if (!$post) {
      $this->forward404();
    }

    return $this->render(array('post' => $post));
  }
  
  /**
   * URLから動的パラメータを受け取り、投稿の編集ページを表示させる
   */
  public function editAction($params) {
    $post = $this->db_manager->get('Posts')->fetchById($params['id']);

    if (!$post) {
      $this->forward404();
    }

    return $this->render(array(
      'post' => $post,
      '_token' => $this->generateCsrfToken('posts/edit'),
    ));
  }

  /**
   * URLから動的パラメータを受け取る
   * HTTPメソッドがPOSTでなければ404に遷移させ、トークンが不正ならリダイレクトさせる
   * 投稿チェックを行い、エラーがなければupdate文を実行し、投稿詳細ページにリダイレクトさせる
   * エラーがある場合は編集ページを再度表示させる (リダイレクトではない)
   */
  public function updateAction($params) {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $post = $this->db_manager->get('Posts')->fetchById($params['id']);

    $token = $this->request->getPost('_token');
    if(!$this->checkCsrfToken('posts/edit', $token)) {
      return $this->redirect('/posts/' . $post['id'] . '/edit');
    }

    $post['contents'] = $this->request->getPost('contents');

    $errors = array();

    if (!strlen($post['contents'])) {
      $errors[] = '感想を入力してください';
    } elseif (mb_strlen($post['contents']) > 200) {
      $errors[] = '感想は200字以内で入力してください';
    }

    if (count($errors) === 0) {
      $user = $this->session->get('user');
      $this->db_manager->get('Posts')->update($post['id'], $post['contents']);
      return $this->redirect('/posts/' . $post['id']);
    }

    return $this->render(array(
      'post' => $post,
      'errors' => $errors,
      '_token' => $this->generateCsrfToken('posts/edit'),
    ), 'edit');
  }
}
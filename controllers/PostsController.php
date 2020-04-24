<?php

class PostsController extends Controller {
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
      // ホントはユーザーページか公演詳細ページに飛ばしたいけどとりあえず
      return $this->redirect('/');
    }

    $user = $this->session->get('user');

    return $this->render(array(
      'errors' => $errors,
      'contents' => $contents,
      '_token' => $this->generateCsrfToken('posts/new'),
    ), 'new');
  }
}
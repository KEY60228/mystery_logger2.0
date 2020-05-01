<?php

class PerformancesController extends Controller {
  // ログインが必要なアクションを指定する
  protected $auth_actions = array('index', 'show');

  /**
   * 公演一覧ページを表示する
   */
  public function indexAction() {
    $performances = $this->db_manager->get('Performances')->fetchAllPerformances();
    
    return $this->render(array(
      'performances' => $performances,
    ));
  }

  /**
   * URLから公演IDを受け取り、公演詳細ページを表示する
   */
  public function showAction($params) {
    $performance = $this->db_manager->get('Performances')->fetchByPerformanceId($params['id']);
    $posts = $this->db_manager->get('Posts')->fetchAllByPerformanceId($params['id']);

    if (!$performance) {
      $this->forward404();
    }

    $user = $this->session->get('user');
    $done = $this->db_manager->get('Dones')->isDone($user['id'], $params['id']);
    $wanna = $this->db_manager->get('Wannas')->isWanna($user['id'], $params['id']);

    return $this->render(array(
      'performance' => $performance,
      'posts' => $posts,
      'done' => $done,
      'wanna' => $wanna,
      '_token' => $this->generateCsrfToken('performances/show'),
    ));
  }

  /**
   * 公演を「行った！」にするアクション
   */
  public function doneAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $performance_id = $this->request->getPost('performance_id');
    if (!$performance_id) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrfToken('performances/show', $token)) {
      return $this->redirect('/performances/' . $performance_id);
    }

    $performance = $this->db_manager->get('Performances')->fetchByPerformanceId($performance_id);
    if (!$performance) {
      $this->forward404();
    }

    $user = $this->session->get('user');
    
    if (!$this->db_manager->get('Dones')->isDone($user['id'], $performance_id)) {
      $this->db_manager->get('Dones')->insert($user['id'], $performance_id);
    }

    return $this->redirect('/performances/' . $performance_id);
  }

  /**
   * 公演の「行った！」を取り消すアクション
   */
  public function undoneAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $performance_id = $this->request->getPost('performance_id');
    if (!$performance_id) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrfToken('performances/show', $token)) {
      return $this->redirect('/performances/' . $performance_id);
    }

    $performance = $this->db_manager->get('Performances')->fetchByPerformanceId($performance_id);
    if (!$performance) {
      $this->forward404();
    }

    $user = $this->session->get('user');
    
    if ($this->db_manager->get('Dones')->isDone($user['id'], $performance_id)) {
      $this->db_manager->get('Dones')->delete($user['id'], $performance_id);
    }

    return $this->redirect('/performances/' . $performance_id);
  }

  /**
   * 公演を「行きたい！」にするアクション
   */
  public function interestedAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $performance_id = $this->request->getPost('performance_id');
    if (!$performance_id) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrfToken('performances/show', $token)) {
      return $this->redirect('/performances/' . $performance_id);
    }

    $performance = $this->db_manager->get('Performances')->fetchByPerformanceId($performance_id);
    if (!$performance) {
      $this->forward404();
    }

    $user = $this->session->get('user');
    
    if (!$this->db_manager->get('Wannas')->isWanna($user['id'], $performance_id)) {
      $this->db_manager->get('Wannas')->insert($user['id'], $performance_id);
    }

    return $this->redirect('/performances/' . $performance_id);
  }

  /**
   * 公演の「行きたい！」を取り消すアクション
   */
  public function disinterestedAction() {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $performance_id = $this->request->getPost('performance_id');
    if (!$performance_id) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrfToken('performances/show', $token)) {
      return $this->redirect('/performances/' . $performance_id);
    }

    $performance = $this->db_manager->get('Performances')->fetchByPerformanceId($performance_id);
    if (!$performance) {
      $this->forward404();
    }

    $user = $this->session->get('user');
    
    if ($this->db_manager->get('Wannas')->isWanna($user['id'], $performance_id)) {
      $this->db_manager->get('Wannas')->delete($user['id'], $performance_id);
    }

    return $this->redirect('/performances/' . $performance_id);
  }

    /**
   * イメージファイルの名前を受け取って返すアクション
   */
  public function imageAction($params) {
    $img_file = $this->application->getPerformanceImagesDir() . '/' . $params['image'];
    $img_info = getimagesize($img_file);
    header('Content-Type: ' . $img_info['mime']);
    readfile($img_file);  
  }
}
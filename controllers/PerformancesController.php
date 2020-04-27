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

    return $this->render(array(
      'performance' => $performance,
      'posts' => $posts,
    ));
  }
}
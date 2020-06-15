<?php

abstract class Controller {
  protected $controller_name;
  protected $action_name;
  protected $application;
  protected $request;
  protected $response;
  protected $session;
  protected $db_manager;
  // ログインが必要なアクションを指定する
  protected $auth_actions = array();
  // なぞログ用追加分
  protected $right_actions = array();

  /**
   * コンストラクタ
   * 
   * 各プロパティに各インスタンス、コントローラ名をセットする
   * 
   * @param Application $application
   */
  public function __construct($application) {
    $this->controller_name = strtolower(substr(get_class($this), 0, -10));
    $this->application = $application;
    $this->request = $application->getRequest();
    $this->response = $application->getResponse();
    $this->session = $application->getSession();
    $this->db_manager = $application->getDbManager();
  }

  /**
   * アクション実行メソッド
   * 
   * 該当するActionメソッドを実行し、そのコンテンツを返す
   * 該当するActionメソッドがない場合は404ページを表示させ、
   * 該当するActionにログイン認証が必要かつ認証されていない場合は例外を返す
   * 該当するActionが権限が必要かつ権限がない場合も例外を返す
   * 
   * @param string $action アクション名
   * @param array $params ルーティングでマッチした配列
   * @return string $content レスポンスで返すコンテンツ
   */
  public function run($action, $params = array()) {
    $this->action_name = $action;
    $action_method = $action . 'Action';

    if (!method_exists($this, $action_method)) {
      $this->forward404();
    }

    if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
      throw new UnauthorizedActionException();
    }
    
    // なぞログ追加分
    // 権限のないアクションに対する例外
    // (もっといいやり方あるかもだけどとりあえず…)
    if (array_key_exists('id', $params)) {
      $viewing_user = $this->session->get('user');
      $viewing_post = $this->db_manager->get('Posts')->fetchById($params['id']);
      if ($this->needsRight($action) && $this->session->isAuthenticated() && $viewing_post['user_id'] !== $viewing_user['id']) {
        throw new NoRightActionException();
      }
    }
    // 追加分ここまで

    $content = $this->$action_method($params);
    return $content;
  }

  /**
   * レンダリングメソッド
   * 
   * ビューファイルに渡す連想配列、テンプレート名、読み込むレイアウトファイル名を受け取り、
   * View::render()を呼び出し、その結果を返す
   * 
   * @param array $variables ビューファイルに渡す変数の配列
   * @param string $template ビューファイル名 / nullの場合はアクション名 (default) null
   * @param string $layout 基盤レイアウトとなるファイル名
   * @return string $view->render() レンダリングしたビューファイルのコンテンツ
   */
  protected function render($variables = array(), $template = null, $layout = 'layout') {
    $defaults = array (
      'request' => $this->request,
      'base_url' => $this->request->getBaseUrl(),
      'session' => $this->session,
    );

    $view = new View($this->application->getViewDir(), $defaults);

    if (is_null($template)) {
      $template = $this->action_name;
    }

    $path = $this->controller_name . '/' . $template;
    return $view->render($path, $variables, $layout);
  }

  /**
   * 404ページへの遷移
   * 
   * @throws HttpNotFoundException
   */
  protected function forward404() {
    throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controller_name . '/' . $this->action_name);
  }

  /**
   * リダイレクトメソッド
   * 
   * 任意のURLを受け取り、リダイレクトさせる
   * その際Response::setStatusCode()でHTTPステータスコードを302にする
   * 
   * @param string $url
   */
  protected function redirect($url) {
    if (!preg_match('#https?://#', $url)) {
      $protocol = $this->request->isSsl() ? 'https://' : 'http://';
      $host = $this->request->getHost();
      $base_url = $this->request->getBaseUrl();
      $url = $protocol . $host . $base_url . $url;
    }
    $this->response->setStatusCode(302, 'Found');
    $this->response->setHttpHeader('Location', $url);
  }

  /**
   * CSRFトークンの生成
   * 
   * フォーム名を受け取り、CSRFトークンを返す
   * 保有できるトークンは最大10個までで、それ以上になる場合は古いものから削除する
   * 
   * @param string $form_name
   * @return string $token
   */
  protected function generateCsrfToken($form_name) {
    $key = 'csrf_tokens/' . $form_name;
    $tokens = $this->session->get($key, array());
    
    if (count($tokens) >= 10) {
      array_shift($tokens);
    }

    $token = sha1($form_name . session_id() . microtime());
    $tokens[] = $token;

    $this->session->set($key, $tokens);

    return $token;
  }

  /**
   * CSRFトークンのチェック
   * 
   * フォーム名とCSRFトークンを受け取り、チェックを行う
   * マッチすればtrue、マッチしなければfalseを返す
   * 
   * @param string $form_name
   * @param string $token
   * @return boolean
   */
  protected function checkCsrfToken($form_name, $token) {
    $key = 'csrf_tokens/' . $form_name;
    $tokens = $this->session->get($key,array());

    if (false !== ($pos = array_search($token,$tokens, true))) {
      unset($tokens[$pos]);
      $this->session->set($key, $tokens);
      return true;
    }

    return false;
  }

  /**
   * アクションの認証要否の判定
   * 
   * アクション名を受け取り、$auth_actionプロパティと照合を行い、
   * ログインが必要なアクションであればtrueを返し、そうでなければfalseを返す
   * 
   * @param string $action
   * @return boolean
   */
  protected function needsAuthentication($action) {
    if ($this->auth_actions === true || (is_array($this->auth_actions) && in_array($action, $this->auth_actions))) {
      return true;
    }
    return false;
  }

  /**
   * アクションの権限要否の判定
   * 
   * アクション名を受け取り、$right_actionプロパティと照合を行い、
   * 権限が必要なアクションであればtrueを返し、そうでなければfalseを返す
   * 
   * @param string $action
   * @return boolean
   */
  protected function needsRight($action) {
    if ($this->right_actions === true || (is_array($this->right_actions) && in_array($action, $this->right_actions))) {
      return true;
    }
    return false;
  }
}
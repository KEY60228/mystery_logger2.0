<?php

/**
 * 抽象クラス Application
 */
abstract class Application {
  protected $debug = false;
  protected $request;
  protected $response;
  protected $session;
  protected $db_manager;
  protected $login_action = array();
  // 追加分
  protected $deny_action = array();

  /**
   * コンストラクタ
   * 
   * @param boolean $debug (default) false
   */
  public function __construct($debug = false) {
    $this->setDebugMode($debug);
    $this->initialize();
    $this->configure();
  }

  /**
   * デバッグモードを設定する
   * 
   * @param boolean $debug
   */
  public function setDebugMode($debug) {
    if ($debug) {
      $this->debug = true;
      ini_set('display_errors', 1);
      error_reporting(-1);
    } else {
      $this->debug = false;
      ini_set('display_errors', 0);
    }
  }

  /**
   * 各クラスの初期化を行う
   */
  protected function initialize() {
    $this->request = new Request();
    $this->response = new Response();
    $this->session = new Session();
    $this->db_manager = new DbManager();
    $this->router = new Router($this->registerRoutes());
  }

  /**
   * 個別のアプリケーションの設定を行う
   */
  protected function configure() {

  }

  /**
   * ルートディレクトリを取得する
   */
  abstract public function getRootDir();

  /**
   * ルーティング定義配列を取得する
   */
  abstract protected function registerRoutes();

  /**
   * デバッグモードか否かを確認する
   * 
   * @return boolean
   */
  public function isDebugMode() {
    return $this->debug;
  }

  /**
   * Requestインスタンスを返す
   * 
   * @return Request
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * Responseインスタンスを返す
   * 
   * @return Response
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * Sessionインスタンスを返す
   * 
   * @return Session
   */
  public function getSession() {
    return $this->session;
  }

  /**
   * DbManagerインスタンスを返す
   * 
   * @return DbManager
   */
  public function getDbManager() {
    return $this->db_manager;
  }

  /**
   * contollersディレクトリまでのパスを返す
   * 
   * @return string
   */
  public function getControllerDir() {
    return $this->getRootDir() . '/controllers';
  }

  /**
   * viewsディレクトリまでのパスを返す
   * 
   * @return string
   */
  public function getViewDir() {
    return $this->getRootDir() . '/views';
  }
  
  /**
   * modelsディレクトリまでのパスを返す
   * 
   * @return string
   */
  public function getModelDir() {
    return $this->getRootDir() . '/models';
  }
 
  /**
   * webディレクトリまでのパスを返す
   * 
   * @return string
   */
  public function getWebDir() {
    return $this->getRootDir() . '/web';
  }

  /**
   * アプリケーションの実行
   * 
   * PATH_INFOとルーティング定義配列のマッチングを行い、
   * runAction()を呼び出した後、Response::send()を呼び出す
   * 
   * @throws HttpNotFoundException ルートがマッチしない場合
   * @throws UnauthorizedActionException ログインが必要なページかつログインしていない場合
   * @throws NoRightActionException アクセス権限がない場合
   */
  public function run() {
    try {
      $params = $this->router->resolve($this->request->getPathInfo());
      
      if ($params === false) {
        throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
      }
      
      $controller = $params['controller'];
      $action = $params['action'];
  
      $this->runAction($controller, $action, $params);
    } catch (HttpNotFoundException $e) {
      $this->render404page($e);
    } catch (UnauthorizedActionException $e) {
      list($controller, $action) = $this->login_action;
      $this->runAction($controller, $action);
    } catch (NoRightActionException $e) {
      // 追加分
      list($controller, $action) = $this->deny_action;
      $this->runAction($controller, $action, $this->session->get('user'));
    }

    $this->response->send();
  }

  /**
   * アプリケーションの実行プロセス
   * 
   * 指定されたコントローラ名でfindController()を実行し、
   * 該当のコントローラクラスのrun()を実行後、Response::setContent()を実行する
   * 
   * @param string $controller_name
   * @param string $action
   * @param array $params マッチした配列 (default) array()
   * 
   * @throws HttpNotFoundException 指定されたコントローラが見つからない場合
   */
  public function runAction($controller_name, $action, $params = array()) {
    $controller_class = ucfirst($controller_name) . 'Controller';
    $controller = $this->findController($controller_class);

    if ($controller === false) {
      throw new HttpNotFoundException($controller_class . ' controller is not found.');
    }

    $content = $controller->run($action, $params);
    $this->response->setContent($content);
  }

  /**
   * 指定されたコントローラ名のControllerインスタンスを返す
   * 
   * コントローラー名を受け取り、該当のControllerインスタンスを返す
   * ファイル、クラスが見つからなかった場合はfalseを返す
   * 
   * @param string $controller_class
   * @return Controller(Application)|false
   */
  protected function findController($controller_class) {
    if (!class_exists($controller_class)) {
      $controller_file = $this->getControllerDir() . '/' . $controller_class . '.php';

      if (!is_readable($controller_file)) {
        return false;
      } else {
        require_once $controller_file;
        if (!class_exists($controller_class)) {
          return false;
        }
      }
    }

    return new $controller_class($this);
  }

  /**
   * 404ページを表示する
   * 
   * 例外を受け取り、404ページを表示させる
   * デバッグモードならエラーメッセージを表示させる
   * 
   * @param Exception $e
   */
  protected function render404page($e) {
    $this->response->setStatusCode(404, 'Not Found');
    $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    $this->response->setContent(<<<EOF
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>404</title>
</head>
<body>
  {$message}
</body>
</html>
EOF
    );
  }
}
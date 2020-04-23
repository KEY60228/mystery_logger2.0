<?php

abstract class Application {
  protected $debug = false;
  protected $request;
  protected $response;
  protected $session;
  protected $db_manager;
  /**
   * ログインが必要なアクションを指定する
   */
  protected $login_action = array();

  /**
   * デバッグを行うか否かの変数を受け取り、各メソッドに渡すコンストラクタ
   */
  public function __construct($debug = false) {
    $this->setDebugMode($debug);
    $this->initialize();
    $this->configure();
  }

  /**
   * デバッグを行うか否かの変数を受け取り、エラー表示処理を切り替える
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
   * Routerクラスにはルーティング定義配列を渡す
   */
  protected function initialize() {
    $this->request = new Request();
    $this->response = new Response();
    $this->session = new Session();
    $this->db_manager = new DbMnagaer();
    $this->router = new Router($this->registerRoutes());
  }

  /**
   * 個別のアプリケーションの設定をする
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
  abstract public function registerRoutes();

  /**
   * デバッグモードか否かを確認する
   */
  public function isDebugMode() {
    return $this->debug;
  }

  /**
   * Requestインスタンスを返す
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * Responseインスタンスを返す
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * Sessionインスタンスを返す
   */
  public function getSession() {
    return $this->session;
  }

  /**
   * DbManagerインスタンスを返す
   */
  public function getDbManager() {
    return $this->db_manager;
  }

  /**
   * contollersディレクトリまでのパスを返す
   */
  public function getControllerDir() {
    return $this->getRootDir() . '/controllers';
  }

  /**
   * viewsディレクトリまでのパスを返す
   */
  public function getViewDir() {
    return $this->getRootDir() . '/views';
  }
  
  /**
   * modelsディレクトリまでのパスを返す
   */
  public function getModelDir() {
    return $this->getRootDir() . '/models';
  }
 
  /**
   * webディレクトリまでのパスを返す
   */
  public function getWebDir() {
    return $this->getRootDir() . '/web';
  }

  /**
   * PATH_INFOとルーティング定義配列のマッチングを行い、マッチしなければ例外を返す
   * マッチしたらrunActionメソッドを呼び出し、Responseクラスのsendメソッドを呼び出す
   */
  public function run() {
    try {
      $params = $this->router->resolve($this->request->getPathInfo());
      
      if ($params === false) {
        throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
      }
      
      $controller = $params['controller'];
      $action = $params['action'];
  
      $this->runAction($contoroller, $action, $params);
    } catch (HttpNotFoundException $e) {
      $this->render404page($e);
    } catch (UnauthorizedActionException $e) {
      list($controller, $action) = $this->login_action;
      $this->runAction($controller, $action);
    }

    $this->response->send();
  }

  /**
   * コントローラーの名前、アクションの名前、マッチした配列を受け取り、
   * findControllerメソッドでコントローラーを探す
   * コントローラーが見つからなかった場合は例外を返し、見つかった場合は
   * 該当のControllerクラスのrunメソッドを実行し、ResponseクラスのsetContentメソッドを呼び出す
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
   * コントローラー名を受け取り、該当のコントローラーファイル、クラスが見つかった場合は
   * 該当のControllerインスタンスを返す
   * ファイル、クラスが見つからなかった場合はfalseを返す
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
   * 例外を受け取り、404ページを表示させる
   * デバッグモードならエラーメッセージを表示させる
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
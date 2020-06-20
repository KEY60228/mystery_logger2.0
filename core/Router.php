<?php

/**
 * ルーティング管理クラス Router
 */
class Router {
  protected $routes;

  /**
   * コンストラクタ
   * 
   * ルーティング定義配列を受け取り、Router::compileRoutes()を呼び出す
   * 
   * @param array $definitions
   */
  public function __construct($definitions) {
    $this->routes = $this->compileRoutes($definitions);
  }

  /**
   * ルーティング定義配列を変換するメソッド
   * 
   * ルーティング定義配列を動的パラメータを正規表現で扱える形式に変換する
   * 
   * @param array $definitions
   * @return array $routes
   */
  public function compileRoutes($definitions) {
    $routes = array();

    foreach ($definitions as $url => $params) {
      $tokens = explode('/', ltrim($url, '/'));
      foreach ($tokens as $i => $token) {
        if (0 === strpos($token,':')) {
          $name = substr($token, 1);
          $token = '(?P<' . $name . '>[^/]+)';
        }
        $tokens[$i] = $token;
      }
      $pattern = '/' . implode('/', $tokens);
      $routes[$pattern] = $params;
    }
    return $routes;
  }

  /**
   * 指定されたPATH_INFOからルーティングを行うメソッド
   * 
   * PATH_INFOを受け取り、ルーティング定義配列とマッチングを行う
   * マッチした場合はコントローラーとアクションが格納された$paramsと
   * マッチしたルートが格納された$matchesをマージし、その配列を返す
   * マッチしなかった場合はfalseを返す
   * 
   * @param string $path_info
   * @return array|false
   */
  public function resolve($path_info) {
    if ('/' !== substr($path_info, 0, 1)) {
      $path_info = '/' . $path_info;
    }

    foreach ($this->routes as $pattern => $params) {
      if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
        $params = array_merge($params, $matches);
        return $params;
      }
    }

    return false;
  }
}


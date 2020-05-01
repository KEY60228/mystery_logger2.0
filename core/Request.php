<?php

class Request {
  /**
   * HTTPメソッドがPOSTかどうか判定する
   * POSTであればtrueを返し、それ以外であればfalseを返す
   */
  public function isPost() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }
    return false;
  }

  /**
   * 任意の値$nameを受け取り、$_GET[$name]の値を返す
   * $_GET[$name]が存在しない場合は$defaultを返す
   */
  public function getGet ($name, $default = null) {
    if (isset($_GET[$name])) {
      return $_GET[$name];
    }
    return $default;
  }

  /**
   * 任意の値$nameを受け取り、$_POST[$name]の値を返す
   * $_POST[$name]が存在しない場合は$defaultを返す
   */
  public function getPost($name, $default = null) {
    if (isset($_POST[$name])) {
      return $_POST[$name];
    }
    return $default;
  }

  /**
   * HTTPリクエストヘッダに含まれるサーバーのホスト名を返す
   * 含まれていない場合はApache側に設定されたホスト名を返す
   */
  public function getHost() {
    if (!empty($_SERVER['HTTP_HOST'])) {
      return $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['SERVER_NAME'];
  }

  /**
   * HTTPSでのアクセスならtrueを、そうでなければfalseを返す
   */
  public function isSsl() {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      return true;
    }
    return false;
  }

  /**
   * リクエストされたURLのうち、ホスト部分以降の値を返す
   */
  public function getRequestUri() {
    return $_SERVER['REQUEST_URI'];
  }

  /**
   * リクエストされたURLのうち、ホスト部分以降フロントコントローラーまでの値(ベースURL)を返す
   * フロントコントローラーも含めてリクエストされている場合はフロントコントローラーの値も含む
   */
  public function getBaseUrl() {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $request_uri = $this->getRequestUri();
    
    if (0 === strpos($request_uri, $script_name)) {
      return $script_name;
    } else if (0 === strpos($request_uri, dirname($script_name))) {
      return rtrim(dirname($script_name), '/');
    }
    return '';
  }

  /**
   * リクエストされたURLのうち、ベースURLより後ろの値(PATH_INFO)を返す
   * GETパラメータの値は含まない
   */
  public function getPathInfo() {
    $base_url = $this->getBaseUrl();
    $request_uri = $this->getRequestUri();

    if (false !== ($pos = strpos($request_uri, '?'))) {
      $request_uri = substr($request_uri, 0, $pos);
    }

    $path_info = (string)substr($request_uri, strlen($base_url));
    return $path_info;
  }

  /**
   * 画像アップロード用に追加
   * 識別子$nameを受け取り、あれば$_FILEを返しなければ$defaultを返す
   */
  public function getFile($name, $default = null) {
    if(isset($_FILES[$name])) {
      return $_FILES[$name];
    }
    return $default;
  }

  /**
   * アップロードされた画像の拡張子を返す
   */
  public function getImageType($filename) {
    switch (exif_imagetype($filename)) {
      case IMAGETYPE_JPEG:
        return 'jpeg';
        break;
      case IMAGETYPE_PNG:
        return 'png';
        break;
      default:
        return null;
    }
  }
}
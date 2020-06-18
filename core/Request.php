<?php

/**
 * クライアントからのリクエスト管理クラス Request
 */
class Request {
  /**
   * HTTPメソッドがPOSTかどうか判定する
   * 
   * POSTであればtrueを返し、それ以外であればfalseを返す
   * 
   * @return boolean
   */
  public function isPost() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }
    return false;
  }

  /**
   * GETパラメータを取得する
   * 
   * 任意の値$nameを受け取り、$_GET[$name]の値を返す
   * $_GET[$name]が存在しない場合は$defaultを返す
   * 
   * @param string $name
   * @param mixed $default
   * @return mixed
   */
  public function getGet ($name, $default = null) {
    if (isset($_GET[$name])) {
      return $_GET[$name];
    }
    return $default;
  }

  /**
   * POSTパラメータを取得する
   * 
   * 任意の値$nameを受け取り、$_POST[$name]の値を返す
   * $_POST[$name]が存在しない場合は$defaultを返す
   * 
   * @param string $name
   * @param mixed $default
   * @return mixed
   */
  public function getPost($name, $default = null) {
    if (isset($_POST[$name])) {
      return $_POST[$name];
    }
    return $default;
  }

  /**
   * ホスト名を取得する
   * 
   * HTTPリクエストヘッダに含まれるサーバーのホスト名を返す
   * 含まれていない場合はApache側に設定されたホスト名を返す
   * 
   * @return string
   */
  public function getHost() {
    if (!empty($_SERVER['HTTP_HOST'])) {
      return $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['SERVER_NAME'];
  }

  /**
   * 通信がSSLかどうか判定する
   * 
   * HTTPSでのアクセスならtrueを、そうでなければfalseを返す
   * 
   * @return boolean
   */
  public function isSsl() {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      return true;
    }
    return false;
  }

  /**
   * リクエストURIを取得する
   * 
   * リクエストされたURLのうち、ホスト部分以降の値を返す
   * 
   * @return string
   */
  public function getRequestUri() {
    return $_SERVER['REQUEST_URI'];
  }

  /**
   * ベースURLを取得する
   * 
   * リクエストされたURLのうち、ホスト部分以降フロントコントローラまでの値を返す
   * フロントコントローラーも含めてリクエストされている場合はフロントコントローラーの値も含む
   * 
   * @return string
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
   * PATH_INFOを取得する
   * 
   * リクエストされたURLのうち、ベースURLより後ろの値を返す
   * GETパラメータの値は含まない
   * 
   * @return string
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
   * Fileパラメータを取得する
   * 
   * 画像アップロード用に追加 (なぞログ)
   * 識別子$nameを受け取り、あれば$_FILEを返しなければ$defaultを返す
   * 
   * @param string $name
   * @param mixed $default
   * @return mixed
   */
  public function getFile($name, $default = null) {
    if(isset($_FILES[$name])) {
      return $_FILES[$name];
    }
    return $default;
  }

  /**
   * アップロードされた画像の拡張子を返す
   * 
   * 画像アップロード用に追加 (なぞログ)
   * jpegかpngのみ対応
   * 
   * @param string $filename
   * @return string|null
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
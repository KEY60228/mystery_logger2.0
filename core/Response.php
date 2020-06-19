<?php

/**
 * クライアントへのレスポンス管理クラス Response
 */
class Response {
  protected $content;
  protected $status_code = 200;
  protected $status_text = 'OK';
  protected $http_headers = array();

  /**
   * レスポンスを送信するメソッド
   * 
   * HTTPのステータスコード、テキスト、内容をHTTP/1.1で送信する
   * HTTPレスポンスヘッダの指定があればそれも送信する
   */
  public function send() {
    header('HTTP/1.1' . $this->status_code . ' ' . $this->status_text);
    
    foreach ($this->http_headers as $name => $value) {
      header($name . ': ' . $value);
    }

    echo $this->content;
  }

  /**
   * クライアントに返す内容を受け取り、$contentに格納するメソッド
   * 
   * @param string $content
   */
  public function setContent($content) {
    $this->content = $content;
  }

  /**
   * HTTPのステータスコード、テキストを受け取り、それぞれ格納するメソッド
   * 
   * @param integer $status_code
   * @param string $status_text
   */
  public function setStatusCode($status_code, $status_text = '') {
    $this->status_code = $status_code;
    $this->status_text = $status_text;
  }

  /**
   * HTTPヘッダの名前と内容を受け取り、連想配列$http_headersに格納するメソッド
   * 
   * @param string $name
   * @param mixed $value
   */
  public function setHttpHeader($name, $value) {
    $this->http_headers[$name] = $value;
  }
}

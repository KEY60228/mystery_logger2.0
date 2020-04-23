<?php

class View {
  protected $base_dir;
  protected $defaults;
  protected $layout_variables = array();

  /**
   * viewsディレクトリとビューファイルにデフォルトで渡したい連想配列の値を受け取り、
   * それぞれ格納するコンストラクタ
   */
  public function __construct($base_dir, $defaults = array()) {
    $this->base_dir = $base_dir;
    $this->defaults = $defaults;
  }

  /**
   * レイアウトに渡す変数と内容を受け取り、連想配列に格納する
   */
  public function setLayoutVar($name, $value) {
    $this->layout_variables[$name] = $value;
  }

  /**
   * ビューファイルへのパス、ビューファイルに渡す変数、読み込むレイアウトファイルを受け取り、
   * アウトプットバッファリングを用いて$contentにビューファイルを格納し、返す
   * レイアウトファイルの指定がある場合は再度実行し、返す
   */
  public function render($_path, $_variables = array(), $_layout = false) {
    $_file = $this->base_dir . '/' . $_path . '.php';
    extract(array_merge($this->defaults, $_variables));
    
    ob_start();
    ob_implicit_flush(0);

    require $_file;

    $content = ob_get_clean();
    
    if ($_layout) {
      $content = $this->render($_layout, array_merge($this->layout_variables, array(
        '_content' => $content,
      )));
    }
    
    return $content;
  }

  /**
   * 文字列を受け取り、エスケープしたものを返す
   */
  public function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
}
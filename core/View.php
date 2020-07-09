<?php

/**
 * ビュー管理クラス View
 */
class View {
  protected $base_dir;
  protected $defaults;
  protected $layout_variables = array();

  /**
   * コンストラクタ
   * 
   * viewsディレクトリとビューファイルにデフォルトで渡したい連想配列の値を受け取り、それぞれ格納する
   * 
   * @param string $base_dir
   * @param array $defaults
   */
  public function __construct($base_dir, $defaults = array()) {
    $this->base_dir = $base_dir;
    $this->defaults = $defaults;
  }

  /**
   * レイアウトに渡す変数と内容を受け取り、連想配列に格納するメソッド
   * 
   * @param string $name
   * @param mixed $value
   */
  public function setLayoutVar($name, $value) {
    $this->layout_variables[$name] = $value;
  }

  /**
   * ビューファイルをレンダリングするメソッド
   * 
   * アウトプットバッファリングを用いて$contentにビューファイルを格納し、返す
   * レイアウトファイルの指定がある場合は再度実行し、返す
   * 
   * @param string $_path
   * @param array $_variables
   * @param mixed $_layout
   * @return string
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
   * 
   * @param string $string
   * @return string
   */
  public function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
}
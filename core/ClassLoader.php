<?php

/**
 * オートロード対応クラス ClassLoader
 */
class ClassLoader {
  protected $dirs;

  /**
   * ClassLoader::loadClass()がオートロード時に呼び出されるよう登録する
   */
  public function register() {
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * オートロード時に探すディレクトリのフルパスを受け取り、登録する
   * 
   * @param string $dir
   */
  public function registerDir($dir) {
    $this->dirs[] = $dir;
  }

  /**
   * オートロードメソッド
   * 
   * 受け取ったクラス名をもつファイルを呼び出す
   * 
   * @param string $class
   */
  public function loadClass($class) {
    foreach ($this->dirs as $dir) {
      $file = $dir . '/' . $class . '.php';
      if (is_readable($file)) {
        require $file;
        return;
      }
    }
  }
}


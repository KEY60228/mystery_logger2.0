<?php

class ClassLoader {
  protected $dirs;

  /**
   * ClassLoaderクラスのloadClassメソッドがオートロード時に呼び出されるよう登録する
   */
  public function register() {
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * オートロード時に探すディレクトリのフルパスを受け取り、登録する
   */
  public function registerDir($dir) {
    $this->dirs[] = $dir;
  }

  /**
   * オートロード時に呼び出されるメソッド
   * クラス名を受け取り、対応するクラスファイルをClassLoaderクラスのregisterDirメソッドで
   * 登録したディレクトリ配下から探し出し、requireする
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


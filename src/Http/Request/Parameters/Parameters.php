<?php

  namespace F\Http;

  use F\Http\Request;

  class Parameters extends Request {
    public static string $param;
    public static array $params = [];

    public function __construct($param) {
      self::$param = $param;
      self::$params = parent::$params;
    }

    public function get() {
      return htmlspecialchars($_GET[ self::$param ]);
    }

    public function print() {
      return print(htmlspecialchars($_GET[ self::$param ]));
    }
  }
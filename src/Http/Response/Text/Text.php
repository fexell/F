<?php
  namespace F\Http\Response;

  use F\Http\Response;

  class Text extends Response {
    public static string $text;

    public function __construct(string $text) {
      self::$text = $text;
    }

    public function print() {
      return print(self::$text);
    }
  }
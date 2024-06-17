<?php
  namespace F\Http\Response;

  use F\Http\Response;

  class Text extends Response {

    /**
     * @var string $text The text to do something with
     */
    public static string $text;

    /**
     * @param string $text The text parameter, to do something with
     */
    public function __construct(string $text) {
      self::$text = $text;
    }

    /**
     * The print function/method to print the text
     */
    public function print() {
      return print(self::$text);
    }
  }
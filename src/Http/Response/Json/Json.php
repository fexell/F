<?php

  namespace F\Http;

  use F\Http\Response;

  class Json extends Response {
    public static string $data;

    public function __construct(string $data) {
      self::$data = $data;
    }

    private function encode() {
      return json_encode($this->data);
    }

    public function print() {
      return print($this->encode());
    }
  }
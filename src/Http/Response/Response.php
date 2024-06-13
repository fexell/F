<?php

  namespace F\Http;

  use F\Http\Json;

  class Response {
    public function json(string $data) {
      return new Json($data);
    }
  }
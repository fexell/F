<?php

  namespace F\Http\Response;

  use F\Http\Response;

  class Status extends Response {

    /**
     * @access private
     * @var int $statusCode The http response code to be set
     */
    private int $statusCode;

    public function __construct(int $statusCode) {
      $this->statusCode = $statusCode;

      // Set the status code to the one passed in the $statusCode variable
      $this->setHttpResponseCode();
    }

    /**
     * @access private
     * @return int Sets and returns the http response status code
     */
    private function setHttpResponseCode() {
      return http_response_code($this->statusCode);
    }
  }
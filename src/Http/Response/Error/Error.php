<?php

  namespace F\Http\Response;

  use F\Http\Response;
  use F\Http\Response\Json;
  use F\Http\Response\Text;

  class Error extends Response {

    private Response $response;

    /**
     * @var Json $json The variable to hold the new Json instance
     */
    private Json $json;

    /**
     * @var string $message The variable to hold the error message
     */
    private static string $message;

    /**
     * @var array $errorData The array with the error data
     */
    private static array | null $errorData = null;

    /**
     * @param string $message The variable to hold the error message
     * @param array $errorData The array with the error data
     */
    public function __construct(string $message, array $errorData = null) {
      $this->response = new parent();

      self::$message = $message;
      self::$errorData = $errorData;
    }

    public function toJson() {
      return $this->response->status(http_response_code() !== 200 && http_response_code() !== 400
      ? http_response_code()
      : 400)->json(
        self::$message,
        [
          'statusCode' => http_response_code(),
          'error' => (self::$errorData ? [ ...self::$errorData ] : (object) []),
        ]);
    }

    public function toPlainText() {
      return $this->response->status(http_response_code() !== 200 && http_response_code() !== 400
      ?  http_response_code()
      : 400)->text(self::$message);
    }

    public function print() {
      return $this->toJson()->print();
    }
  }
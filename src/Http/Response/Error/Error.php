<?php

  namespace F\Http\Response;

  require_once __DIR__ . '/../Status/StatusCodes.php';

  use F\Http\Response;
  use F\Http\Response\Json;
  use F\Http\Response\HttpStatusCodes;
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

    /**
     * Convert/return the error as json
     * @return Json Returns the error message and error data as json
     */
    public function toJson() {
      return $this->response->status(http_response_code() !== 200 && http_response_code() !== 400
      ? http_response_code()
      : HttpStatusCodes::BAD_REQUEST)->json(
        self::$message,
        [
          'statusCode' => http_response_code(),
          'error' => (self::$errorData ? [ ...self::$errorData ] : (object) []),
        ]);
    }

    /**
     * Return the error (only the error message) as plain text
     * @return Text Returns the error message as plain text
     */
    public function toPlainText() {
      return $this->response->status(http_response_code() !== 200 && http_response_code() !== 400
      ?  http_response_code()
      : HttpStatusCodes::BAD_REQUEST)->text(self::$message);
    }

    /**
     * Print the error as json
     * @return string Prints the error object (error message, and error data) as json
     */
    public function print() {
      return $this->toJson()->print();
    }
  }
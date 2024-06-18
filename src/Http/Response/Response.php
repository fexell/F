<?php

  namespace F\Http;

  require_once __DIR__ . '/Error/Error.php';
  require_once __DIR__ . '/Json/Json.php';
  require_once __DIR__ . '/Status/Status.php';
  require_once __DIR__ . '/Text/Text.php';

  use F\Http\Response\Error;
  use F\Http\Response\Json;
  use F\Http\Response\Status;
  use F\Http\Response\Text;

  /**
   * The Response class
   * 
   * This class handles responses, like printing json, returning errors, etc.
   * 
   * @access public
   */
  class Response {
    
    /**
     * The method for the Error class, to handle errors
     * @param string $message The error message
     * @param array $errorData The error data as an array
     * @return Error Return a new instance of the Error class
     */
    public function error(string $message, array | null $errorData = null) {
      return new Error($message, $errorData);
    }

    /**
     * The method for the Json class, to handle json
     * @param string|array $dataOrMessage Either the message or an array, to be turned into json
     * @param array|null $data Is set to null as default; otherwise the array to be turned into json
     * @return Json Return a new instance of the Json class
     */
    public function json(string | array $dataOrMessage, array | null $data = null) {
      return new Json($dataOrMessage, $data);
    }

    /**
     * The method for the Status class, to handle http response codes
     * @param int $statusCode The "http response code" to set
     * @return Status Return a new instance of the Status class
     */
    public function status(int $statusCode) {
      return new Status($statusCode);
    }

    /**
     * The method for the Text class, to handle plain text methods, etc.
     * @param string $text The text to be returned in the text methods (in the Text folder -> Text.php file)
     * @return Text Return a new instance of the text class
     */
    public function text(string | array $text) {
      return new Text($text);
    }
  }
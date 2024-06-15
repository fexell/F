<?php

  namespace F\Http\Response;

  use F\Http\Response;
  use F\Http\Response\Error;
  use F\Http\Response\Status;

  class Json extends Response {

    public Response $response;

    /**
     * @access public
     * @var string|array $dataOrMessage Can be either a string, or an array
     */
    public static string | array $dataOrMessage;

    /**
     * @access public
     * @var array|null $data Is set to null as default, otherwise needs to be an array
     */
    public static array | null $data;

    /**
     * @param string|array $dataOrMessage Either the message or an array, to be turned into json
     * @param array|null $data Is set to null as default; otherwise the array to be turned into json
     */
    public function __construct(string | array $dataOrMessage, array | null $data = null) {
      $this->response = new parent();

      self::$dataOrMessage = $dataOrMessage;
      self::$data = $data;
    }

    /**
     * Method for checking if $dataOrMessage is either a string or array,
     * if $data is present, and then return an array of the items set
     * @access private
     */
    private function checkAndReturnArray() {

      // If the $dataOrMessage variable is a string, and $data is null,
      // return the message as an array element, to be turned into json later
      if(is_string(self::$dataOrMessage) && is_null(self::$data))
        return [ 'message' => self::$dataOrMessage ];

      // Else if the $dataOrMessage variable is a string, and $data is an array,
      // return an array, with the message and the $data
      else if(is_string(self::$dataOrMessage) && is_array(self::$data))
        return [
          'message' => self::$dataOrMessage,
          ...self::$data
        ];

      // Else if the $dataOrMessage is a string, and $data is an array, as well $data has
      // a $data property, return the message, data, and "unpack" the rest of the $data array
      else if(is_string(self::$dataOrMessage)
      && is_array(self::$data)
      && is_array(self::$data[ 'data' ]))
        return [
          'message' => self::$dataOrMessage,
          'data' => self::$data[ 'data' ],
          ...self::$data
        ];

      // Else if $dataOrMessage is of type array, and $data is null, just return $dataOrMessage
      // as an array
      else if(is_array(self::$dataOrMessage) && is_null(self::$data))
        return [ ...self::$dataOrMessage ];

      // Return an error message if none of the previous if/elseif statements returned true
      else
        return $this->response->status(400)->error('In the json method, the first parameter needs to be either a string or array.')->print();
    }

    /**
     * @access private
     * @return string Returns the returned array from checkAndReturnArray()-method, and turns it into json
     */
    private function encoded() {
      return json_encode($this->checkAndReturnArray());
    }

    /**
     * @access public
     * @return string Prints the json encoded array (from checkAndReturnArray()-method)
     */
    public function print() {
      return print($this->encoded());
    }
  }
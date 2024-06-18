<?php

  namespace F\Http\Response;

  /**
   * Status codes as an enum
   */
  enum HttpStatusCodes: int {

    // 200
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NO_CONTENT = 204;
    case RESET_CONTENT = 205;
    case PARTIAL_CONTENT = 206;

    // 300
    case MULTIPLE_CHOICES = 300;
    case MOVED_PERMANENTLY = 301;
    case FOUND = 302;
    case SEE_OTHER = 303;
    case NOT_MODIFIED = 304;
    case USE_PROXY = 305;
    case UNUSED = 306;
    case TEMPORARY_REDIRECT = 307;

    // 400
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case PAYMENT_REQUIRED = 402;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case NOT_ACCEPTABLE = 406;
    case PROXY_AUTHENTICATION_REQUIRED = 407;
    case REQUEST_TIMEOUT = 408;
    case CONFLICT = 409;
    case GONE = 410;
    case LENGTH_REQUIRED = 411;
  }
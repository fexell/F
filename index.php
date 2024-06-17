<?php

  require './src/Autoloader.php';

  use F\App;
  use F\Router;

  $app = new App;

  $app->router('/')->get(function($request, $response) {
    # $response->status(400)->json('Hello World!')->print();
    $response->status(404)->error('Hello World! This is an error!')->print();
  });

  $app->run();
<?php

  require './src/Autoloader.php';

  use F\App;
  use F\Router;

  $app = new App;

  $app->router('/')->get(function($request, $response) {
    # $response->text($request->params('id')->get())->print();
    $request->params('id')->print();
  });

  $app->run();
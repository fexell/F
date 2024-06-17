<?php

  require './src/Autoloader.php';

  use F\App;
  use F\Router;

  $app = new App;

  $app->router('/:key')->get(function($request, $response) {
    $request->params('key')->toJson()->print();
  });

  $app->run();
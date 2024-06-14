<?php

  require('./src/Autoloader.php');

  use F\App;
  use F\Router;

  $app = new App;

  $app->router('/:key/:id')->get(function($request) {
    $request->params('key')->print();
  });

  $app->run();
<?php

  require './src/Autoloader.php';

  use F\App;
  use F\Router;

  $app = new App;

  $app->router('/:key')->get(function($request, $response) {
    
  });

  $app->run();
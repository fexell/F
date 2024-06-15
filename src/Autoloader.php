<?php

  spl_autoload_register(function ($class) {
    $paths = glob(__DIR__ . '/*');
    $file = str_replace('F\\', '/', $class) . '.php';

    foreach($paths as $path)
      if(file_exists($path . $file))
        require_once($path . $file);
  });
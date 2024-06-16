<?php

  /**
   * Autoload files
   */
  spl_autoload_register(function ($class) {

    // Get all folder paths inside the ./src folder, and return it as an array
    $paths = glob(__DIR__ . '/*');

    // Replace the namespace (followed by a backslash) with a forwards slash in the class's name,
    // and append .php, to get the file name to be included/required
    $file = str_replace('F\\', '/', $class) . '.php';

    // Foreach folder-path in the $paths array
    foreach($paths as $path)
      if(file_exists($path . $file)) // If the file exists, then...
        require_once($path . $file); // ...require it once (require_once)
  });
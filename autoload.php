<?php
spl_autoload_register(function ($class) {
  // Base directory for PhpSpreadsheet namespace prefix
  $phpSpreadsheetBaseDir = __DIR__ . '/plugins/PhpSpreadsheet/src/PhpSpreadsheet/';

  // Base directory for Psr namespace prefix
  $psrBaseDir = __DIR__ . '/plugins/Psr/SimpleCache/src/';

  // Base directory for ZipStream namespace prefix
  $zipStreamBaseDir = __DIR__ . '/plugins/ZipStream/src/';

  // Project-specific namespace prefixes
  $prefixes = [
      'PhpOffice\\PhpSpreadsheet\\' => $phpSpreadsheetBaseDir,
      'Psr\\SimpleCache\\' => $psrBaseDir,
      'ZipStream\\' => $zipStreamBaseDir,
  ];

  // Iterate through prefixes and attempt to load the corresponding file
  foreach ($prefixes as $prefix => $baseDir) {
      $len = strlen($prefix);
      if (strncmp($prefix, $class, $len) === 0) {
          // Get the relative class name
          $relative_class = substr($class, $len);
          // Replace the namespace prefix with the base directory, replace namespace
          // separators with directory separators in the relative class name, append
          // with .php
          $file = $baseDir . str_replace('\\', '/', $relative_class) . '.php';
          // If the file exists, require it
          if (file_exists($file)) {
              require $file;
              return;
          }
      }
  }
});
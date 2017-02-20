<?php
/**
 * @file
 * Contains
 */


set_time_limit(0);

$autoloaders = [];

if (file_exists(__DIR__ . '/../autoload.local.php')) {
  include_once __DIR__ . '/../autoload.local.php';
} else {
  $autoloaders = [
    __DIR__ . '/../../../autoload.php',
    __DIR__ . '/../vendor/autoload.php'
  ];
}

foreach ($autoloaders as $file) {
  if (file_exists($file)) {
    $autoloader = $file;
    break;
  }
}

if (isset($autoloader)) {
  $autoload = include_once $autoloader;
} else {
  echo ' You must set up the project dependencies using `composer install`' . PHP_EOL;
  exit(1);
}

//$argvInput = new \Symfony\Component\Console\Input\ArgvInput();
//$debug = $argvInput->hasParameterOption(['--debug']);

$application = new \Legovaer\DrupalDeploy\Application();
$application->setDefaultCommand('about');
$application->addCommands([new \Legovaer\DrupalDeploy\Command\DeployCommand()]);
$application->run();

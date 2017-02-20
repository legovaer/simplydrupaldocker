<?php

namespace Legovaer\DrupalDeploy;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication {
  /**
   * @var string
   */
  const NAME = 'Simply Drupal Docker';

  /**
   * @var string
   */
  const VERSION = '1.0.0';

  /**
   * @var string
   */
  protected $commandName;

  /**
   * ConsoleApplication constructor.
   *
   * @param string             $name
   * @param string             $version
   */
  public function __construct() {
    parent::__construct(self::NAME, self::VERSION);
  }
}
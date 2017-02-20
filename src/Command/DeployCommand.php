<?php
/**
 * @file
 * Contains
 */

namespace Legovaer\DrupalDeploy\Command;

use Nubs\RandomNameGenerator\Alliteration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class DeployCommand extends Command {

  protected function configure() {
    $this->setName('deploy:drupal')
      ->setDescription('Deploys a Drupal container.');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    if (!$this->checkForDocker()) {
      $output->writeln("<error>Unable to find docker. Make sure that both docker & docker-compose are available</error>");
      return;
    }

    $this->updateYaml();
    $output->writeln("<info>Downloading Drupal...</info>");
    if (!$this->installDrupal()) {
      $output->writeln("<error>A problem occured while downloading Drupal.. Aborting..</error>");
      return;
    }
    $output->writeln("<info>Starting Docker containers ...</info>");
    $this->startDocker();

  }

  protected function startDocker() {
    $dir = getcwd() . "/docroot";
    echo shell_exec("cd $dir && docker-compose up");
  }
  protected function checkForDocker() {
    return (exec("which docker") && exec("which docker-compose"));
  }

  protected function installDrupal() {
    $dir = getcwd() . "/docroot";
    try {
      echo shell_exec("cp $dir/web/sites/default/default.settings.php $dir/web/sites/default/settings.php");
      $settings = '
$databases["default"]["default"] = [
  "database" => "drupal",
  "username" => "drupal",
  "password" => "drupal",
  "host" => "mariadb",
  "driver" => "mysql"
];
';
      file_put_contents("$dir/web/sites/default/settings.php", $settings, FILE_APPEND);
      echo shell_exec("cp " . getcwd() . "/lib/docker-compose.yml $dir/");
      return TRUE;
    }
    catch (\Exception $e) {
      return FALSE;
    }
  }

  protected function updateYaml() {
/*    $yaml = Yaml::parse(file_get_contents(getcwd().'/lib/docker-compose.yml'));
    $yaml["services"]["mariadb"]["MYSQL_DATABASE"] = $this->name;
    $yaml["services"]["mariadb"]["environment"]["MYSQL_DATABASE"] = $this->name;
    $yaml["services"]["php"]["environment"]["PHP_SITE_NAME"] = $this->name;
    $yaml["services"]["php"]["environment"]["PHP_HOST_NAME"] = $this->name.":8000";
    $new_yaml = Yaml::dump($yaml);
    file_put_contents(getcwd().'/lib/docker-compose.yml', $new_yaml);
    //var_dump(Yaml::dump($yaml));*/
  }

}
<?php
namespace CliLab\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CommandBase extends Command
{
  private $gitlabClient = null;
  private $gitlabMe = null;

  protected $input = null;
  protected $output = null;

  // Override these functions in derived classes ----------------------------

  abstract protected function createName();
  abstract protected function createDescription();
  abstract protected function createHelp();
  abstract protected function doit();

  protected function createDefinition()
  {
    return array();
  }

  // Internal ---------------------------------------------------------------

  protected function configure()
  {
    $this->setName($this->createName())->setDescription($this->createDescription())
      ->setHelp($this->createHelp())->setDefinition($this->createDefinition());

    $this->gitlabClient = new \Gitlab\Client(getenv('GITLAB_API_ENDPOINT'));
    $this->gitlabClient->authenticate(getenv('GITLAB_API_PRIVATE_TOKEN'), \Gitlab\Client::AUTH_URL_TOKEN);

    $this->gitlabMe = $this->client('users')->me();
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->input = $input;
    $this->output = $output;

    return $this->doit();
  }

  protected function client($method)
  {
    return $this->gitlabClient->api($method);
  }

  protected function me()
  {
    return $this->gitlabMe;
  }

  protected function myId()
  {
    return $this->me()['id'];
  }

  protected function emphMe($id, $string = null)
  {
    if (is_null($string)) {
      $string = $id;
    }

    return $this->myId() == $id ? "<comment>{$string}</comment>" : $string;
  }
}

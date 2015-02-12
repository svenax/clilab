<?php
namespace CliLab\Command;

use Symfony\Component\Console\Helper\Table;

use CliLab\Utils\StringUtils;

class SnippetsCommand extends CommandBase
{
  protected function createName()
  {
    return 'snippets';
  }

  protected function createDescription()
  {
    return 'Work with snippets';
  }

  protected function createHelp()
  {
    return <<<EOF
Lorem ipsum.
EOF;
  }

  protected function doit()
  {
    $projects = $this->client('projects')->accessible();
    foreach ($projects as $project) {
      if ($project['name']) {
      }
    }
    $snippets = $this->client('snippets')->all(12);
    $result = array();
    foreach ($snippets as $snippet) {
      $result[] = array(
        StringUtils::truncate($snippet['title'], 50),
        $this->emphMe($snippet['author']['id'], $snippet['author']['username']),
        $snippet['id'],
      );
    }
    sort($result);

    $table = new Table($this->output);
    $table->setHeaders(['Title', 'Owner', 'ID']);
    $table->setRows($result);
    $table->render();

    return 0;
  }
}


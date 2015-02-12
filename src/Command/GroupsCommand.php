<?php
namespace CliLab\Command;

use Symfony\Component\Console\Helper\Table;

class GroupsCommand extends CommandBase
{
  protected function createName()
  {
    return 'groups';
  }

  protected function createDescription()
  {
    return 'Show groups';
  }

  protected function createHelp()
  {
    return <<<EOF
Lorem ipsum.
EOF;
  }

  protected function doit()
  {
    $groups = $this->client('groups')->all();

    $result = array();
    foreach ($groups as $group) {
      $result[] = array(
        $group['name'],
        $group['id'],
      );
    }
    sort($result);

    $table = new Table($this->output);
    $table->setHeaders(['Name', 'ID']);
    $table->setRows($result);
    $table->render();

    return 0;
  }
}

<?php
namespace CliLab\Command;

use Symfony\Component\Console\Helper\Table;

class UsersCommand extends BaseCommand
{
  protected function createName()
  {
    return 'users';
  }

  protected function createDescription()
  {
    return 'Show user info';
  }

  protected function createHelp()
  {
    return <<<EOF
Lorem ipsum.
EOF;
  }

  protected function doit()
  {
    $users = $this->client('users')->all();

    $result = array();
    foreach ($users as $user) {
      if ($user['state'] === 'active') {
        $result[] = array(
          $this->emphMe($user['id'], $user['name']),
          $this->emphMe($user['id'], $user['username']),
          $this->emphMe($user['id']),
        );
      }
    }
    sort($result);

    $table = new Table($this->output);
    $table->setHeaders(['Full Name', 'User', 'ID']);
    $table->setRows($result);
    $table->render();

    return 0;
  }
}

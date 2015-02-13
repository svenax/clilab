<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Finder\Finder;

$pharFile = 'clilab.phar';

if (file_exists($pharFile)) {
  unlink($pharFile);
}

$phar = new \Phar($pharFile, 0, $pharFile);
$phar->setSignatureAlgorithm(\Phar::SHA1);

$phar->startBuffering();

$finder = new Finder();
$finder->files()
  ->ignoreVCS(true)
  ->name('*.php')
  ->in(__DIR__.'/src');
foreach ($finder as $file) {
  addFile($phar, $file);
}

$finder = new Finder();
$finder->files()
  ->ignoreVCS(true)
  ->name('*.php')
  ->exclude('Tests')
  ->exclude('test')
  ->in(__DIR__.'/vendor/symfony/')
  ->in(__DIR__.'/vendor/kriswallsmith/')
  ->in(__DIR__.'/vendor/m4tthumphrey/');
foreach ($finder as $file) {
  addFile($phar, $file);
}

addFile($phar, new \SplFileInfo(__DIR__.'/vendor/autoload.php'));
addFile($phar, new \SplFileInfo(__DIR__.'/vendor/composer/autoload_classmap.php'));
addFile($phar, new \SplFileInfo(__DIR__.'/vendor/composer/autoload_namespaces.php'));
addFile($phar, new \SplFileInfo(__DIR__.'/vendor/composer/autoload_psr4.php'));
addFile($phar, new \SplFileInfo(__DIR__.'/vendor/composer/autoload_real.php'));
addFile($phar, new \SplFileInfo(__DIR__.'/vendor/composer/ClassLoader.php'));

addCliLabBin($phar);
$phar->setStub(getStub());

$phar->stopBuffering();

unset($phar);

function addFile($phar, $file)
{
  $localPath = strtr(str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file->getRealPath()), '\\', '/');
  $phar->addFile($file, $localPath);
}

function addCliLabBin($phar)
{
  $content = file_get_contents(__DIR__.'/bin/clilab');
  $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
  $phar->addFromString('bin/clilab', $content);
}

function getStub()
{
  return <<<'EOF'
#!/usr/bin/env php
<?php
/*
 * This file is part of CliLab.
 *
 * © Sven Axelsson, 2015
 */

Phar::mapPhar('clilab.phar');
require 'phar://clilab.phar/bin/clilab';

__HALT_COMPILER();
EOF;
}

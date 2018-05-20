<?php
/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  20.05.2018
 */

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();
$command = new \PhpFqcnParser\Console\RunCommand();
$application->add($command);
$application->setDefaultCommand($command->getName(), true);
$application->run();

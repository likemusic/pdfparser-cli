#!/usr/bin/env php
<?php

require __DIR__ .'/../vendor/autoload.php';

use Likemusic\PhpParser\CLI\Commands\GetTextCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new GetTextCommand());
$application->run();

<?php

define("BASEPATH", dirname(__FILE__, 2));

require_once(BASEPATH.'/bootstrap.php');

$application = $container->get('console.application');

$application->run();

<?php
require(__DIR__ . "/vendor/autoload.php");

if (!class_exists("Config")) {
    require(__DIR__ . "/classes/Config.php");
}

if (!isset($config)) {
    $config = new Config(__DIR__ . "/config/config.json");
}

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array(__DIR__ . "/entities");
$isDevMode = $config->application->debug;

$dbParams = array();
foreach ($config->database as $key => $value) {
    
    if ($value != "") {
        $dbParams[$key] = $value;
    }
}

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

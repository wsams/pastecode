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
$isDevMode = true;

echo "<pre>";

$dbParams = array();
foreach ($config->database as $key => $value) {
    //var_dump($key, $value);
    if ($value != "") {
        $dbParams[$key] = $value;
    }
}

/*
$dbParams = array(
    "driver"    => $config->database->driver,
    "user"      => $config->database->user,
    "password"  => $config->database->password,
    "dbname"    => $config->database->dbname,
    "host"	=> $config->database->host
);
*/
$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

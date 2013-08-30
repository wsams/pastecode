<?php

// Include the composer autoloader
require(__DIR__ . "/../vendor/autoload.php");

// Get our configuration
require(__DIR__ . "/../classes/Config.php");
$config = new Config(__DIR__ . "/../config/config.json");

// Declare our namespaces
use \Slim\Slim;
use \Slim\Views\Twig as Twig;

// Define some options relating to our template
$options = array(
    "output" => $config->interface->output,
    "layout" => $config->interface->layout
);

// Instantiate everything we need for the application
$twigView = new Twig();
$twigView->twigTemplateDirs = __DIR__ . "/../templates";

require(__DIR__ . "/../templates/extensions/PasteSetupTwigExtension.php");


$twigView->parserExtensions = array(new PasteSetupTwigExtension());
$app = new Slim(array("view" => $twigView,"mode" => $config->application->mode,"debug" => $config->application->debug));

// Here are our routes
$app->get(
    "/",
    function () use ($app, $options) {
        $app->render("pages/setup.twig", $options);
    }
);

$app->get(
    "/config/:driver",
    function ($driver) use ($app, $options) {
        $options["driver"] = $driver;
        $options["output"] = "ajax";
        $app->render("pages/setupDriver.twig", $options);
    }
);

$app->run();
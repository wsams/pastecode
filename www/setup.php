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

$app->post(
    "/",
    function () use ($app, $options) {
        $post = $app->request()->params();
        $config = array(
            "server" => array(
                "url" => $post["url"],
                "timezone" => $post["timezone"]
            ),
            "email" => array(
                "name" => $post["email_from"],
                "address" => $post["email_address"]
            ),
            "application" => array(
                "mode" => "prod",
                "debug" => false
            ),
            "interface" => array(
                "output" => "html5",
                "layout" => "basic"
            )
        );
        
        if ($post["driver"] == "pdo_sqlite") {
            $driverParams = array("driver","user","password","path");
        } else {
            $driverParams = array("driver","user","password","host","port","dbname");
        }
        
        foreach ($driverParams as $param) {
            if (isset($post[$param])) {
                $config["database"][$param] = $post[$param];
            }
        }
        $config = json_encode($config);
        file_put_contents(__DIR__ . "/../config/config.json", stripslashes($config));
        //unlink(__FILE__);
    }
);

$app->run();
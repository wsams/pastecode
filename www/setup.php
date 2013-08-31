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
        
        // Create the database
        unset($config);
        require(__DIR__ . "/../entities/Paste.php");
        require(__DIR__ . "/../entities/User.php");
        require(__DIR__ . "/../bootstrap.php");
        $tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
        $classes = array(
            $entityManager->getClassMetadata('User'),
            $entityManager->getClassMetadata('Paste')
        );
        $tool->createSchema($classes);
        
        // Create the anonymous user
        $user = new User();
        $user->setUsername("Anonymous");
        $user->setPassword(password_hash(mt_rand(10000,99999).time(), PASSWORD_BCRYPT));
        $user->setEmail("anonymous@nowhere.org");
        $user->setFirstName("Anonymous");
        $user->setLastName("User");
        $user->setRegisteredOn(new DateTime("now", new DateTimeZone($config->server->timezone)));
        $user->setActivationKey(sha1(mt_rand(10000,99999).time()."noactivate"));
        $user->setActivated(true);
        $entityManager->persist($user);
        $entityManager->flush();
        
        // Delete the setup file
        unlink(__FILE__);
        
        // Redirect to the index page
        $app->redirect("/");
    }
);

$app->run();
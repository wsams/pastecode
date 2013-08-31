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
    "layout" => $config->interface->layout,
    "siteRoot" => $config->server->url
);

// Instantiate everything we need for the application
$twigView = new Twig();
$twigView->twigTemplateDirs = __DIR__ . "/../templates";

require(__DIR__ . "/../templates/extensions/PasteTwigExtension.php");

$twigView->parserExtensions = array(new PasteTwigExtension());
$app = new Slim(array("view" => $twigView,"mode" => $config->application->mode,"debug" => $config->application->debug));

// Is the user logged in?
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true && $_SESSION["ipAddress"] = $_SERVER["REMOTE_ADDR"]) {
    $options["user"] = array(
        "loggedIn" => true,
        "username" => $_SESSION["username"]
    );
} else {
    $options["user"] = array(
        "loggedIn" => false
    );
}

// Here are our routes
$app->get(
    "/",
    function () use ($app, $options) {
        $app->render("pages/index.twig", $options);
    }
);

$app->get(
    "/user/register",
    function () use ($app, $options) {
        $app->render("pages/register.twig", $options);
    }
);

$app->post(
    "/user/register",
    function () use ($app, $options) {
        
        // Require the libraries we need to make our doctrine stuff go
        require(__DIR__ . "/../bootstrap.php");
        require(__DIR__ . "/../entities/User.php");
        
        // Pull the post to a variable
        $post = $app->request()->params();
        
        // Make sure the user doesn't already exist
        $query = $entityManager->createQuery("select u.id from User u where u.username = '{$post["username"]}'");
        
        if (count($query->getResult())) {
            $options["result"] = "dupeUser";
        } elseif ($post["password"] == $post["password2"]) {
            $user = new User();
            $user->setUsername($post["username"]);
            $user->setPassword(password_hash($post["password"], PASSWORD_BCRYPT));
            $user->setEmail($post["email"]);
            $user->setFirstName($post["firstName"]);
            $user->setLastName($post["lastName"]);
            $user->setRegisteredOn(new DateTime("now", new DateTimeZone($config->server->timezone)));
            $user->setActivationKey(sha1(mt_rand(10000,99999).time().$post["email"]));
            $user->setActivated(false);
            try {
                
                // Store the user in the database
                $entityManager->persist($user);
                $entityManager->flush();
                $options["result"] = "registered";
                
                // Send the activation code email
                $transport = Swift_MailTransport::newInstance();
                $message = Swift_Message::newInstance()
                    ->setSubject("Pastecode - New account activation information")
                    ->setFrom(array($config->email->address => $config->email->name))
                    ->setTo(array($post["email"]))
                    ->setBody("Your activation code is {$user->getActivationKey()}.
                              Go to the page below to activate your account.
                              {$config->server->url}/user/activate?email={$user->getEmail()}&key={$user->getActivationKey()}");
                $transport->send($message);
                
            } catch(Exception $e) {
                var_dump($e);
                $options["result"] = "error";
            }
        } else {
            $options["result"] = "passwordMismatch";
        }
        
        $app->render("pages/postRegister.twig", $options);
    }
);

$app->get(
    "/user/activate",
    function () use ($app, $options) {
        $get = $app->request()->params();
        $options["get"] = $get;
        $app->render("pages/activate.twig", $options);
    }
);

$app->post(
    "/user/activate",
    function () use ($app, $options) {
        $post = $app->request()->params();
        
        // Require the libraries we need to make our doctrine stuff go
        require(__DIR__ . "/../bootstrap.php");
        require(__DIR__ . "/../entities/User.php");
        
        // Get our user
        $query = $entityManager->createQuery("select u from User u where u.email = '{$post["email"]}'");
        $results = $query->getResult();
        
        if (count($results) && $results[0]->getActivationKey() == $post["key"]) {
            $results[0]->setActivated(true);
            $entityManager->persist($results[0]);
            $entityManager->flush();
            $options["result"] = "activated";
        } else {
            $options["result"] = "error";
        }
        
        $app->render("pages/postActivate.twig", $options);
    }
);

$app->get(
    "/user/login",
    function () use ($app, $options) {
        $app->render("pages/login.twig", $options);
    }
);

$app->post(
    "/user/login",
    function () use ($app, $options) {
        $post = $app->request()->params();
        
        // Require the libraries we need to make our doctrine stuff go
        require(__DIR__ . "/../bootstrap.php");
        require(__DIR__ . "/../entities/User.php");
        
        // Get our user
        $query = $entityManager->createQuery("select u from User u where u.username = '{$post["username"]}'");
        $results = $query->getResult();
        
        if (count($results) && password_verify($post["password"], $results[0]->getPassword())) {
            $_SESSION["loggedIn"] = true;
            $_SESSION["username"] = $post["username"];
            $_SESSION["ipAddress"] = $_SERVER["REMOTE_ADDR"];
            $app->redirect("/");
        } else {
            $options["message"] = "Invalid Credentials. Please try again.";
            $app->render("pages/login.twig", $options);
        }
    }
);

$app->get(
    "/user/logout",
    function () use ($app, $options) {
        $_SESSION["loggedIn"] = false;
        unset($_SESSION["username"], $_SESSION["ipAddress"]);
        $app->redirect("/");
    }
);

$app->post(
    "/paste/create",
    function () use ($app, $options) {
        $post = $app->request()->params();
        
        // Require the libraries we need to make our doctrine stuff go
        require(__DIR__ . "/../bootstrap.php");
        require(__DIR__ . "/../entities/Paste.php");
        require(__DIR__ . "/../entities/User.php");
        
        if ($options["user"]["loggedIn"] == true) {
            $username = $options["user"]["username"];
        } else {
            $username = "Anonymous";
        }
        
        $query = $entityManager->createQuery("select u from User u where u.username = '{$username}'");
        $owner = $query->getResult();
        $owner = $owner[0];
        
        $paste = new Paste();
        $paste->setTitle($post["title"]);
        $paste->setOwner($owner);
        $paste->setUniqueKey(sha1(mt_rand(10000,99999).time().$post["title"].$post["language"].$post["contents"]));
        $paste->setPostedOn(new DateTime("now", new DateTimeZone("America/Indiana/Indianapolis")));
        $paste->setExpiresOn(new DateTime($post["expiresOn"], new DateTimeZone("America/Indiana/Indianapolis")));
        $paste->setLanguage($post["language"]);
        $paste->setVisibility($post["visibility"]);
        $paste->setContents($post["contents"]);
        
        $entityManager->persist($paste);
        $entityManager->flush();
        
        $app->redirect("/paste/view/{$paste->getUniqueKey()}");
    }
);

$app->get(
    "/paste/view/:uniqueKey",
    function ($uniqueKey) use ($app, $options) {
        
        // Require the libraries we need to make our doctrine stuff go
        require(__DIR__ . "/../bootstrap.php");
        require(__DIR__ . "/../entities/Paste.php");
        require(__DIR__ . "/../entities/User.php");
        
        if ($options["user"]["loggedIn"] == true) {
            $username = $options["user"]["username"];
        } else {
            $username = "Anonymous";
        }
        
        // Get our paste from the database
        $query = $entityManager->createQuery("select p from Paste p where p.uniqueKey = '{$uniqueKey}'");
        $paste = $query->getResult();
        if (count($paste) > 0 ) {
            $paste = $paste[0];
            
            // Make sure we should display it
            if ($paste->getVisibility() < 2 || $paste->getVisibility() >= 2 && $username == $paste->getOwner()->getUsername()) {
                $options["paste"] = $paste;
            } else {
                $app->redirect("/error");
            }
            
            $app->render("pages/viewPaste.twig", $options);
        } else {
            $app->redirect("/error");
        }
    }
);

$app->get(
    "/error",
    function () use ($app, $options) {
        $app->render("pages/viewPasteError.twig", $options);
    }
);

// Run our application
$app->run();
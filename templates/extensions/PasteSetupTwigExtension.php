<?php
/**
 * This is pastecode paste extension. It provides access to everything we need
 * to draw the paste section of the front end.
 *
 * @version 1.0
 * @author Anthony Vitacco <avitacco@iu.edu>
 */
class PasteSetupTwigExtension extends Twig_Extension
{
    
    /**
     * get Functions
     * This returns an array of function references to expose to the
     * templates themselves
     *
     * @return array The list of functions
     */
    public function getFunctions()
    {    
        return array(
            "getURL" => new Twig_Function_Method($this, "getURL"),
            "getTimeZones" => new Twig_Function_Method($this, "getTimeZones"),
            "getDatabaseDrivers" => new Twig_Function_Method($this, "getDatabaseDrivers"),
            "getDriverConfigFields" => new Twig_Function_Method($this, "getDriverConfigFields"),
            "canDeleteSetupPage" => new Twig_Function_Method($this, "canDeleteSetupPage"),
            "canWriteConfig" => new Twig_Function_Method($this, "canWriteConfig"),
        );
    }
    
    /**
     * get Globals
     * This returns a list of global variables we want to keep around
     * In our case we have the superglobals listed here because
     * they aren't otherwise available in twig
     * 
     * @return array The list of global variables
     */
    public function getGlobals()
    {    
        return array (
            "server" => $_SERVER,
            "get" => $_GET,
            "post" => $_POST
        );
    }
    
    /**
     * get Name
     * This returns the name of this class
     */
    public function getName()
    {
        return "setup";
    }
    
    /**
     *
     */
    public function getURL()
    {
        $protocol = "http";
        if (isset($_SERVER["HTTPS"])) {
            $protocol .= "s";
        }
        
        $domain = $_SERVER["SERVER_NAME"];
        $path = dirname($_SERVER["REQUEST_URI"]);
        
        $url = "{$protocol}://{$domain}";
        
        if (!empty($path) && $path != "/") {
            $url .= "/{$path}";
        }
        
        return $url;
    }
    
    /**
     *
     */
    public function getTimeZones()
    {
        return DateTimeZone::listIdentifiers();
    }
    
    /**
     *
     */
    public function getDatabaseDrivers()
    {
        $drivers = array(
            "pdo_sqlite" => "SQLite",
            "pdo_mysql" => "MySQL",
            "pdo_pgsql" => "PostgreSQL",
            "pdo_oci" => "Oracle",
            "pdo_sqlsrv" => "SQL Server"
        );
        
        return $drivers;
    }
    
    /**
     *
     */
    public function getDriverConfigFields($driver)
    {
        // Define fields array with common fields
        $fields = array(
            "user" => array("label" => "Username", "type" => "text"),
            "password" => array("label" => "Password", "type" => "password"),
            "host" => array("label" => "Server", "type" => "text"),
            "port" => array("label" => "Port", "type" => "number"),
            "dbname" => array("label" => "Database", "type" => "text")
        );
        
        // SQLite doesn't need a host, port or db name, but it does need a path
        if ($driver == "pdo_sqlite") {
            unset($fields["host"], $fields["port"], $fields["dbname"]);
            $fields["path"] = array("label" => "Database File", "type" => "text");
        }
        
        return $fields;
    }
    
    /**
     *
     */
    public function canDeleteSetupPage()
    {
        $file = __DIR__ . "/../../www/setup.php";
        return is_writable($file);
    }
    
    /**
     *
     */
    public function canWriteConfig()
    {
        $file = __DIR__ . "/../../config/config.json";
        return is_writable($file);
    }
    
}

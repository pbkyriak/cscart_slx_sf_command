<?php
/**
 * @author Panos Kyriakakis <panos at salix.gr>
 */
use Symfony\Component\Console\Application;
use Slx\CommandBridge\CommandLoader;
use Tygh\Registry;

@ini_set('memory_limit', '256M');
error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/CSboot4.php');

// cscart version check
if (defined('PRODUCT_TYPE'))
    $type = PRODUCT_TYPE;
else
    $type = 'PROFESSIONAL';

list($major, $minor, $build) = explode('.', PRODUCT_VERSION);
$version = $major + $minor / 10 + $build / 1000;
if ($version < 4.2) {
    die("Not compatible with versions prior to 4.0\n");
}

$application = new Application();
$application->setName('CS-Cart Command-line Tools');
$loader = new CommandLoader(Registry::get('config.dir.addons'));
$loader->registerCommands($application);

$application->run();

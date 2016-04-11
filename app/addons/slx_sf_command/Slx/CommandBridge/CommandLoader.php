<?php
namespace Slx\CommandBridge;

use Symfony\Component\Finder\Finder;
/**
 * Registers any commands defined in addons. 
 * The command file must be prefixed with 'Command' and be namespaced,
 * also must be a subclass of Symfony\Component\Console\Command\Command
 * To namespace a class: if addon dir is app/addons/slx_test then create
 * a folder for the root namespace eg Slx and then folders for your needs.
 * Example: 
 *  - a command is in Slx/Command folder then its namespace is Slx\Command
 *  - a command is in Tygh/Slx/Command folder then its namespace is Tygh\Slx\Command
 * Naming convension:
 *  DeleteProductsCommand.php   -> class name: DeleteProductsCommand
 * 
 * @author Panos Kyriakakis <panos@salix.gr>
 * @since 11 Απρ 2016
 */
class CommandLoader {
    private $addonsDir;
    
    public function __construct($addonsDir) {
        $this->addonsDir = $addonsDir;
    }
    
    public function registerCommands($application) {
        $installedAddons = array();
        $addons = fn_get_addons(array());
        foreach($addons[0] as $addon) {
            if( $addon['status']=='A') {
                $installedAddons[] = $addon['addon'];
            }
        }
        
        if (!class_exists('Symfony\Component\Finder\Finder')) {
            throw new \RuntimeException('You need the symfony/finder component to register bundle commands.');
        }
        foreach($installedAddons as $addon) {
            $this->registerAddonCommands($application, $addon);
        }
    }
    
    private function registerAddonCommands($application, $addon) {
        $finder = new Finder();
        $finder->files()->name('*Command.php')->in($this->addonsDir.$addon);
        foreach ($finder as $file) {
            $class = str_replace(DIRECTORY_SEPARATOR,'\\', str_replace($this->addonsDir.$addon.DIRECTORY_SEPARATOR,'',$file->getPath()).'/'.$file->getBasename('.php'));
            $r = new \ReflectionClass($class);
            if ($r->isSubclassOf('Symfony\\Component\\Console\\Command\\Command') && !$r->isAbstract() && !$r->getConstructor()->getNumberOfRequiredParameters()) {
                $application->add($r->newInstance());
            }
        }
        
    }
    
}

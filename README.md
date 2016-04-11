# cscart_slx_sf_command
Adds symfony commands to cscart addons

Symfony components console and finder must be added to your cs-cart installation. 
Since version 4.3.4 console is already installed by finder is not.

To add composer requirements to cscart do:
```bash
cd app/lib
composer require symfony/console
composer require symfony/finder
```
supposing that composer is installed in your system already.
Install and enable the addon and you can add symfony like commands to any of your addons. 

The command file must be postfixed with 'Command' and be namespaced, also must be a subclass of Symfony\Component\Console\Command\Command
To namespace a class: if addon dir is app/addons/slx_test then create
a folder for the root namespace eg Slx and then folders for your needs.
Example: 
 - a command is in Slx/Command folder then its namespace is Slx\Command
 - a command is in Tygh/Slx/Command folder then its namespace is Tygh\Slx\Command
 Naming convension:
   DeleteProductsCommand.php   -> class name: DeleteProductsCommand

You can execute your commands using console.php script found in your shop's root just like any symfony/console.
The script bootstraps cs-cart and in your commands you have access to all cs-cart functions and classes just like in a controller script. 

Example command
```php
<?php

namespace Slx\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of TestACommand
 *
 * @author Panos Kyriakakis <panos@salix.gr>
 * @since 11 Απρ 2016
 */
class TestACommand extends Command {

    protected function configure() {
        $this
                ->setName('slx:testa')
                ->setDescription('Changes products status if their stock is bellow a limit.')
                ->addArgument('status', InputArgument::REQUIRED, 'D')
                ->addArgument('stock_limit', InputArgument::REQUIRED, 3)
                ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $status = $input->getArgument('status');
        if( !in_array($status, array('A', 'H', 'D')) ) {
            $output->writeln("<error>Invalid status, status can be A,H,D</error>");
            return 123;
        }
        $stockLimit = (int)$input->getArgument('stock_limit');
        $productIDs = db_get_field("select product_id from ?:products where amount<=?i", $stockLimit);
        $output->writeln(sprintf("Products matching: %s", count($productIDs)));
    }

}
```

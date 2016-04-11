# cscart_slx_sf_command
Adds symfony commands to cscart addons

Symfony components console and finder must be added to your cs-cart installation. 
Since version 4.3.4 console is already installed by finder is not.

To add composer requirements to cscart do:

cd app/lib
composer require symfony/console
composer require symfony/finder

supposing that composer is installed in your system already.
Enable the addon and you can add symfony like commands to any of your addons. 

The command file must be postfixed with 'Command' and be namespaced, also must be a subclass of Symfony\Component\Console\Command\Command
To namespace a class: if addon dir is app/addons/slx_test then create
a folder for the root namespace eg Slx and then folders for your needs.
Example: 
 - a command is in Slx/Command folder then its namespace is Slx\Command
 - a command is in Tygh/Slx/Command folder then its namespace is Tygh\Slx\Command
 Naming convension:
   DeleteProductsCommand.php   -> class name: DeleteProductsCommand

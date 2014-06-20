BootstrapBundle
===============

A set of common templates and utilities to assist in rapid application development in Symfony.

Installation
------------
###Using composer
``` bash
$ composer require vivait/bootstrap-bundle
```

###Enabling bundle
``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Vivait\BootstrapBundle\VivaitBootstrapBundle()
    );
}
```

###Add the config rules
Add the following to your config.yml to enable Mopa Bootstrap integration:
```yaml
mopa_bootstrap:
    form:
        show_legend: false
```

Add the following to your config.yml to enable Mopa Bootstrap integration in to Assetic:
```yaml
assetic:
    bundles:        [VivaitBootstrapBundle,MopaBootstrapBundle]
    filters:
        lessphp:
            apply_to: "\.less$"
            formatter: "compressed"
            preserve_comments: false
            presets:
                my_variable: "#000"
        cssrewrite: ~
    assets:
        bootstrap_css:
            inputs:
                - %kernel.root_dir%/../vendor/twbs/bootstrap/less/bootstrap.less
            filters:
                - lessphp
```

You may already have an assetic configuration in your config,yml, if this is the case then you should combine the two, e.g.
```yaml
assetic:
    debug:          %assetic_debug%
    use_controller: false
    bundles:        [VivaitBootstrapBundle,MopaBootstrapBundle]
    filters:
        lessphp:
            apply_to: "\.less$"
            formatter: "compressed"
            preserve_comments: false
            presets:
                my_variable: "#000"
        cssrewrite: ~
    assets:
        bootstrap_css:
            inputs:
                - %kernel.root_dir%/../vendor/twbs/bootstrap/less/bootstrap.less
            filters:
                - lessphp
```

Add the following to your config.yml to enable Viva Bootstrap form integration:
```yaml
twig:
    form:
        resources:
            - 'VivaitBootstrapBundle:Form:fields.html.twig'
    globals:
        viva_app_name: My App name
```

You may already have a twig configuration in your config,yml, if this is the case then you should combine the two, e.g.
```yaml
twig:
    debug:            %kernel.debug%
    strict_variables: %kernel.debug%
    form:
        resources:
            - 'VivaitBootstrapBundle:Form:fields.html.twig'
    globals:
        viva_app_name: My App name
```

You can change ```viva_app_name``` to be the title of your application, this will then appear in the title of each page.

###Dumping the Assetic files
```sh
php app/console mopa:bootstrap:symlink:less
php app/console mopa:bootstrap:install:font
php app/console assets:install --symlink
php app/console assetic:dump
php app/console cache:clear
```

#### Updating composer.json
To make Mopa bootstrap bundle perform the symlink automatically on install, add the following to your composer.json:
````json
    "scripts": {
        "post-install-cmd": [
            "Mopa\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::postInstallSymlinkTwitterBootstrap"
        ],
        "post-update-cmd": [
            "Mopa\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::postInstallSymlinkTwitterBootstrap"
        ]
    },
````

## Enabling the search box
To enable the search box, you need to define the route to the search controller in your ```config.yml```, e.g:
```yaml
twig:
    globals:
        vivait_search_path: myapp_customers_search
```

This will then pass the search query to your controller, via the ```query``` GET parameter.

## Using KNP Menus
###Using composer
``` bash
$ composer require knplabs/knp-menu-bundle
```

###Enabling bundle
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
    	new Knp\Bundle\MenuBundle\KnpMenuBundle()
    );
}

```

## Adding menu items
To add menu items, you need to create an event listener that will listen to the ```vivait.bootstrap.menu_configure``` event:
```php
<?php
// src/MyApp/MyBundle/EventListener.php
namespace MyApp\MyBundle\EventListener;

use Vivait\BootstrapBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener {
    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event) {
        $menu = $event->getMenu()
            ->getChild('main');

        $members = $menu->addChild('Customers', array(
            'dropdown' => true,
            'caret'    => true,
        ));

        $members->addChild('Dashboard', array(
            'icon'  => 'home',
            'route' => 'myapp_customers_list'
        ));
        
        $members->addChild('Add new', array(
            'icon'  => 'plus',
            'route' => 'myapp_customers_add'
        ));
        
        // ... etc.
    }
}
?>
```

You'll then need to configure this event in your ```services.yml```:
```yaml
myapp.mybundle.configure_menu_listener:
    class: MyApp\MyBundle\EventListener\ConfigureMenuListener
    tags:
     - { name: kernel.event_listener, event: vivait.bootstrap.menu_configure, priority: -2, method: onMenuConfigure }
```

BootstrapBundle
===============
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vivait/BootstrapBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vivait/BootstrapBundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/vivait/BootstrapBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/vivait/BootstrapBundle/build-status/master)

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
Note: Bootstrap 3.1 and above use additional features in less which are incompatible with the LESS compiler, as such we have stripped them out until a solution can be found
```yaml
assetic:
    bundles:        [VivaitBootstrapBundle,MopaBootstrapBundle]
    filters:
        cssrewrite: ~
```

You may already have an assetic configuration in your config,yml, if this is the case then you should combine the two, e.g.
```yaml
assetic:
    debug:          %assetic_debug%
    use_controller: false
    bundles:        [VivaitBootstrapBundle,MopaBootstrapBundle]
    filters:
        cssrewrite: ~
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

## Using the list hydrator
Bootstrap bundle includes a custom Doctrine hydrator, based on [this blog post](https://techpunch.co.uk/development/create-custom-doctrine2-hydrator-symfony2).

> This hydrator is pretty straightforward, it examines the columns returned in each row of the resultset, if there are only two columns, the first is assumed to be the key field (which would normally be the objects ID) and the second is assumed to be the value field. If there are more than two columns per row then the returned array will be an ID indexed array with each row consisting of an array of the remaining column values.

To enable use of the hydrator, add the following to your config.yml:

```yaml
orm:
  hydrators:
    ListHydrator: \Vivait\BootstrapBundle\Hydrator\ListHydrator
```

and use when retrieving results from a query:

```php
$results = $this->getDoctrine()->getManager()->createQuery('{query}')->getResult('ListHydrator');
```

## Using the user callable
At some point in your application, you may wish to inject the current user via the container. Bootstrap provides a helper class for this, based on [this StackOverflow answer](http://stackoverflow.com/questions/22128402/symfony2-injecting-security-context-to-get-the-current-user-how-to-avoid-a-s).

Simply inject ```vivait.bootstrap.user.callable```, like in the following

```yaml
class: \My\Class
arguments: [@vivait.bootstrap.user.callable]
```

Then when you need to reference the current user in your class, just call ```userCallable::getCurrentUser```, as follows:

```php
private $userCallable;

function __construct(UserCallable $userCallable) {
    $this->userCallable = $userCallable;
}

public function mailCurrentUser() {
    mail($userCallable->getCurrentUser()->getEmail(), 'Example', 'Please don\'t actually use this example method!');
}
```

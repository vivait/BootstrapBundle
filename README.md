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
```bash
php app/console mopa:bootstrap:symlink:less
php app/console mopa:bootstrap:install:font
php app/console assets:install --symlink
php app/console assetic:dump
php app/console cache:clear
```

## Enabling the search box
To enable the search box, you need to define the route to the search controller in your ```config.yml```, e.g:
```yaml
twig:
    globals:
        vivait_search_path: vivait_customers_search
```

This will then pass the search query to your controller, via the ```query``` GET parameter.

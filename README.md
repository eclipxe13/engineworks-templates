# eclipxe/engineworks-templates - PHP Templates with plugins

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Scrutinizer][badge-quality]][quality]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

This library is just for running PHP Templates.
Similar projects: [Slim/Php-View](https://github.com/slimphp/PHP-View)

PHP is a powerfull template engine by itself, you might not need a template library as Twig, Plates or Smarty.
It depends on the problem you are facing, maybe you are working with a legacy system, maybe you just don't want it.

# Instalation

Use composer to install this library `composer require eclipxe/engineworks-templates`

# Basic use

```php
<?php namespace EngineWorks\Templates;

// create callables
$callables = new Callables();
$callables->attachAll([
    new Plugins\HtmlEscape(),
    new Plugins\FormatNumber(),
    new Plugins\Transliterate(),
]);

// create resolver
$resolver = new Resolver(__DIR__ . '/templates');

// create templates
$templates = new Templates($callables, $resolver);

// fetch the content of a template (templates/user-details.php)
/* @var $user array */
$content = $templates->fetch('user-details', ['user' => $user]);

// do whatever with the response, I will just echo it
echo $content;
```

## EngineWorks\Templates\Templates

This class works as a factory of `Template` objects,
it helps to locate this objects with a common `Callables` object,
in a common directory with a common file extension.

The most common used method would be `fetch`.
It simply creates a `Template` using the file specified by directory + name + extension, with the default callables
Then will call `fetch` on that template.

## EngineWorks\Templates\Template

This is the main class of the library, it can be created stand alone
or by `Templates::create` (non-static call).

### EngineWorks\Templates\Template::fetch

`fetch` method receives two arguments, a template name and a variables array.
 It will resolve the file name using the Resolver object.
 It will convert the array to variables (using `extract`) in order to make accesible these variables to the file.
 
# Inside the template

The template file is a PHP file, it will have all the variables that were set to fetch method.
Also, you can use `$this`, wich is refered to the `Template` object.

The `$this` object offer some functions registered in the `Callables` object, in the example above the `$callables`
was attatched with the following functions:

- `e($string, $this->getDefaultHtmlFlags())`: escape as html
- `js($string)`: escape as javascript
- `ejs($string)`: escape as html and then as javascript
- `uri($string)`: escape as uri (see `rawurlencode`)
- `url($url, $vars)`: create an url with the defined `$vars`
- `qry($vars)`: create a query string with the defined `$vars`
- `fn($number, $decimals = $this->getDefaultDecimals())`: return a formatted number
- `tr($message, $arguments, $encoder = $this->getDefaultEncoder())`: return a transliterated message, very useful for
  inline templates, like `hello {name}, I sent you an email to {email}`

So, you can use those functions using `$this` inside your template.

Also, you can use the `fetch` method to retrieve the content of another template.

This is a template example `templates/users-list.php`:

```php
<?php
/* @var $pagename string */
/* @var $users array */
?>
<h1><?=$this->e($pagename)?></h1>
<ul>
<?php foreach ($users as $user): ?>
    <li>User: <b><?=$this->tr('{fullname} ({nickname})', $user)?></b></li>
<?php endforeach; ?>
</ul>
```

This is the templates call:

```php
<?php
/* @var $templates \EngineWorks\Templates\Templates */
$templates->fetch('users-list', [
    'pagename' => 'List of users & members',
    'users' => [
        ['fullname' => 'John Doe', 'nickname' => 'jdoe'], 
        ['fullname' => 'Carlos C Soto', 'nickname' => 'eclipxe'], 
    ],
]);
```

This would be the result:

```html
<h1>List of users &amp; members</h1>
<ul>
    <li>User: <b>John Doe (jdoe)</b></li>
    <li>User: <b>Carlos C Soto (eclipxe)</b></li>
</ul>
```
## Integrate with PSR-7

In order to integrate with a PSR-7 compatible library you can use the method `render`.
This method is act as a decorator to fetch the template and write the contents into
the `ResponseInterface` object.

You only need to use this method in case you are using a PSR-7 compatible library.
Otherwise, I recommend you to use `fetch` method. As this is optional, the psr/http-message package
is not a composer dependence but a suggestion.

## Integrate with Slim 4

To use this library in Slim 4 we provide a plugin named `Slim4Plugin` that offers two methods:
- `pathFor`: shortcut for `\Slim\Interfaces\RouteParserInterface::urlFor` method
- `baseUrl`: return `baseUrl` property (setup from `\Slim\App::getBasePath`)

This is a common code to attach the plugin into the `Callables` collection:

```php
<?php
/* @var $callables \EngineWorks\Templates\Callables */
/* @var $app Slim\App */
$callables->attach(new \EngineWorks\Templates\Slim\Slim4Plugin(
    $app->getRouteCollector()->getRouteParser(),
    $app->getBasePath()
));
```

## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details
and don't forget to take a look on the [TODO][] and [CHANGELOG][] files.

## Copyright and License

The `EngineWorks\Templates` library is copyright © [Carlos C Soto](https://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

[contributing]: https://github.com/eclipxe13/engineworks-templates/blob/master/CONTRIBUTING.md
[changelog]: https://github.com/eclipxe13/engineworks-templates/blob/master/CHANGELOG.md
[todo]: https://github.com/eclipxe13/engineworks-templates/blob/master/TODO.md

[source]: https://github.com/eclipxe13/engineworks-templates
[release]: https://github.com/eclipxe13/engineworks-templates/releases
[license]: https://github.com/eclipxe13/engineworks-templates/blob/master/LICENSE
[build]: https://github.com/eclipxe13/engineworks-templates/actions/workflows/build.yml?query=branch:main
[quality]: https://scrutinizer-ci.com/g/eclipxe13/engineworks-templates?branch=master
[coverage]: https://scrutinizer-ci.com/g/eclipxe13/engineworks-templates/?branch=master
[downloads]: https://packagist.org/packages/eclipxe/engineworks-templates

[badge-source]: http://img.shields.io/badge/source-eclipxe13/engineworks--templates-blue.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/eclipxe13/engineworks-templates.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/github/workflow/status/eclipxe13/engineworks-templates/build/main?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/eclipxe13/engineworks-templates/master.svg?style=flat-square
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/eclipxe13/engineworks-templates/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/engineworks-templates.svg?style=flat-square

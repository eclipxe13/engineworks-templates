# eclipxe/engineworks-templates - PHP Templates with plugins

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Scrutinizer][badge-quality]][quality]
[![SensioLabsInsight][badge-sensiolabs]][sensiolabs]
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
namespace EngineWorks\Templates;

// create callables
$callables = new Callables();
$callables->attachAll([
    new Plugins\HtmlEscape(),
    new Plugins\FormatNumber(),
    new Plugins\Transliterate(),
]);

// create templates
$templates = new Templates(__DIR__ . '/../templates', $callables);

// fetch the content of a template
$content = $templates->fetch('user-details', ['user' => $user]);

// do whatever with the response, in the folowing case I will create a PSR-7 Response
$response = new Response();
$response->getBody()->write($content);
```

## EngineWorks\Templates\Templates

This class works as a factory of `Template` objects,
it helps to locate this objects with a common `Callables` object,
in a common directory with a common file extension.

The most common used method would be `fetch`.
It simply create a `Template` using the file specified by directory + name + extension, with the default callables
Then will call `fetch` on that template.

## EngineWorks\Templates\Template

This is the main class of the library, it can be created stand alone
or by `Templates::create` (non-static).

A `Template` has a read-only property accesible by `callables()` method.
It is not a good idea to implement the magic method `__get` for only this property.

### EngineWorks\Templates\Template::fetch

`fetch` method receives two arguments, a filename and a variables array.
 It will convert the array to variables (using `extract`) in order to make accesible this variables to the file.
 
# Inside the template

The template file is a PHP file, it will have all the variables that were set to fetch method.
Also you can use `$this`, wich is refered to the `Template` object.

The `$this` object offer some functions registered in the `Callables` object, in the example above the `$callables`
was attatched with the following functions:

- `e($string, $this->getDefaultHtmlFlags())`: escape as html
- `js($string)`: escape as javascript
- `ejs($string)`: escape as html and then as javascript
- `uri($string)`: escape as uri (see `rawurlencode`)
- `url($url, $vars)`: create a url with the defined `$vars`
- `qry($vars)`: create a query string with the defined `$vars`
- `fn($number, $decimals = $this->getDefaultDecimals())`: return a formatted number
- `tr($message, $arguments, $encoder = $this->getDefaultEncoder())`: return a transliterated message, very useful for
  inline templates, like `hello {name}, I sent you an email to {email}`

So, you can use those functions using `$this` inside your template.

This is a template example `users-list.php`:

```php
<h1><?=$this->e($pagename)?></h1>
<ul>
<?php foreach ($users as $user): ?>
    <li>User: <b><?=$this->tr('{fullname} ({nickname})', $user)?></b></li>
<?php endforeach; ?>
</ul>
```

This is the templates call:

```php
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
Otherwise I recommend you to use `fetch` method. As this is optional, the psr/http-message package
is not a composer dependence but a suggestion.

## Integrate with Slim 3

To use this library in Slim 3 we provide a plugin named `SlimPlugin` that offers two methods:
- `pathFor`: shortcut for `\Slim\Interfaces\RouterInterface::pathFor` method
- `baseUrl`: return baseUrl property (setup from `\Slim\Http\Uri::getBaseUrl`)

This is a common code to attach the plugin into the `Callables` collection:

```php
/* @var $callables \EngineWorks\Templates\Callables */
/* @var $container['router'] \Slim\Interfaces\RouterInterface */
/* @var $request \Slim\Http\Request */
$callables->attach(new \EngineWorks\Slim\SlimPlugin(
    $container['router'],
    $request->getUri()->getBasePath()
));
```

## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details.
Take a look in the TODO section.

## TODO

- [X] Release first stable version
- [X] Publish on github & packagist
- [ ] Add library tools
    - [ ] Travis
    - [ ] Scrutinizer
    - [ ] Insight Sensiolabs
    - [ ] Coveralls
- [ ] Document all the things!

## Copyright and License

The EngineWorks\Templates library is copyright © [Carlos C Soto](https://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

[contributing]: https://github.com/eclipxe13/engineworks-templates/blob/master/CONTRIBUTING.md
[changelog]: https://github.com/eclipxe13/engineworks-templates/blob/master/TODO.md

[source]: https://github.com/eclipxe13/engineworks-templates
[release]: https://github.com/eclipxe13/engineworks-templates/releases
[license]: https://github.com/eclipxe13/engineworks-templates/blob/master/LICENSE
[build]: https://travis-ci.org/eclipxe13/engineworks-templates
[quality]: https://scrutinizer-ci.com/g/eclipxe13/engineworks-templates/
[sensiolabs]: https://insight.sensiolabs.com/projects/eeb7099d-e35d-4acb-8ce2-457004a47913
[coverage]: https://coveralls.io/github/eclipxe13/engineworks-templates?branch=master
[downloads]: https://packagist.org/packages/eclipxe/engineworks-templates

[badge-source]: http://img.shields.io/badge/source-eclipxe13/engineworks--templates-blue.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/eclipxe13/engineworks-templates.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/eclipxe13/engineworks-templates.svg?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/eclipxe13/engineworks-templates/master.svg?style=flat-square
[badge-sensiolabs]: https://img.shields.io/sensiolabs/i/eeb7099d-e35d-4acb-8ce2-457004a47913.svg?style=flat-square
[badge-coverage]: https://coveralls.io/repos/github/eclipxe13/engineworks-templates/badge.svg?branch=master
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/engineworks-templates.svg?style=flat-square

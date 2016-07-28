# EngineWorks\Templates - PHP Templates

This library is just for running PHP Templates.
Similar projects: Slim/Php-View

# Instalation

To install this library you must add this to your `composer.json` file:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://gitlab.com/eclipxe13/templates"
        }
    ],
    "require": {
        "engineworks/dbal": "dev-master"
    }
}
```

# Basic use

```php
namespace EngineWorks\Templates;

// create callables
$callables = new Callables();
$callables->attatchAll([
    new Plugins\HtmlEscape(),
    new Plugins\FormatNumber(),
    new Plugins\Transliterate(),
]);

// create templates
$templates = new Templates(__DIR__ . '/../templates', $callables);

// fetch the content of a template
$content = $templates->fetch('user-details', ['user' => getUserDetails(9)]);

// do whatever with the response, in this case I will create a PSR-7 Response
$response = new Response();
$response->getBody()->write($content);
```

## EngineWorks\Templates\Templates

This class works as a factory of `Template` objects,
it helps to locate this objects with a common `Callables` object,
in a common directory with a common file extension.

The most common used method would be `fetch`.
It simply create a `Template` using the file specified by directory + name + extension, with the default callables
Then will call fetch on that template.

## EngineWorks\Templates\Template

This is the main class of the library, it can be created stand alone
or by `Templates::create`.

A `Template` has a read-only property (accesible by `callables()` method).
No, it is not a good idea to implement the magic method `__get` for only this property.

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
- `uri($string)`: escape as uri (see `rawurlencode`)
- `url($url, $vars)`: create a url with the defined `$vars`
- `qry($vars)`: create a query string with the defined `$vars`
- `fn($number, $decimals = $this->getDefaultDecimals())`: return a formatted number
- `tr($message, $arguments, $encoder = $this->getDefaultEncoder())`: return a transliterated message, very useful for
  inline templates, like `hello {name}, i sent you an email to {email}`

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

## Contributing

Contributions are welcome! Please read [CONTRIBUTING] for details.
Take a look in the TODO section.

## TODO

- [ ] Release first stable version
- [ ] Publish on github & packagist
- [ ] Add library tools
    - [ ] Travis
    - [ ] Scrutinizer
    - [ ] Insight Sensiolabs
    - [ ] Coveralls

## Copyright and License

The EngineWorks\Templates library is copyright © [Carlos C Soto](https://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

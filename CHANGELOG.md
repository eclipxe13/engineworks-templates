# version 2.0.1
- Add support for php-cs-fixer
- Remove coveralls: README.md, composer.json, .travis.yml
- Rename .php_cs to .php_cs.dist and phpunit.xml to phpunit.xml.dist
- Travis-CI: Add php 7.1, add php-cs-fixer, remove coveralls
- Update .gitattributes
- Update license year
- Update sensiolabs badge

# version 2.0.0
- Create `Resolver` class to retrieve the location of the template based on a template name
    - This allow to call `fetch` inside a `Template object` to include other template
    - This allow to implement different logic to retrieve the template file location
- `Templates` remove the logic of resolver, add logic for `defaultResolver`
- `Template` allows to set a resolver, if none provided it creates a Resolver object
- Include dependences to upload coverage to scrutinizer and coveralls
- This breaks compatibility with previous versions

# version 1.0.1
- Looks like I should not test readable against '/dev/console', travis consider the file as readable
- Improve the test to avoid build failure

# version 1.0.0
- Rename project as eclipxe/engineworks-templates and make it available under packagist
- Rename methods attach, attachAll, detach because of typos (they ended with -tch)
- Add support for Slim3 with the plugin `EngineWorks\Slim\SlimPlugin`
- Add support for PSR7 with the method `Template::render`
- Move to github
- Ping travis (remember to add the project to travis before publish it)

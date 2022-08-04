# eclipxe/engineworks-templates - Changelog

## Version 3.0.0

- Change minimal version to PHP 7.3.
- Update license year.
- Remove `SlimPlugin`, replace it with `Slim4Plugin`.
- Code is now fully typed and uses strict types.

### Development changes

- Move development tools to Phive.
- Migrate from Travis-CI to GitHub Workflows.

## Version 2.0.2

- Remove dependency of slim/php-view
- Install scrutinizer/ocular only on travis
- Avoid package versions @stable
- Package phpunit/phpunit is version 5.X to check over php 5.6

## Version 2.0.1

- Add support for php-cs-fixer
- Remove coveralls: README.md, composer.json, .travis.yml
- Rename .php_cs to .php_cs.dist and phpunit.xml to phpunit.xml.dist
- Travis-CI: Add php 7.1, add php-cs-fixer, remove coveralls
- Update .gitattributes
- Update license year
- Update sensiolabs badge

## Version 2.0.0

- Create `Resolver` class to retrieve the location of the template based on a template name
    - This allows to call `fetch` inside a `Template object` to include other template
    - This allows to implement different logic to retrieve the template file location
- `Templates` remove the logic of resolver, add logic for `defaultResolver`
- `Template` allows to set a resolver, if none provided it creates a Resolver object
- Include dependences to upload coverage to scrutinizer and coveralls
- This breaks compatibility with previous versions

## Version 1.0.1

- Looks like I should not test readable against '/dev/console', travis consider the file as readable
- Improve the test to avoid build failure

## Version 1.0.0

- Rename project as eclipxe/engineworks-templates and make it available under packagist
- Rename methods attach, attachAll, detach because of typos (they ended with -tch)
- Add support for Slim3 with the plugin `EngineWorks\Slim\SlimPlugin`
- Add support for PSR7 with the method `Template::render`
- Move to GitHub
- Ping travis (remember to add the project to travis before publish it)

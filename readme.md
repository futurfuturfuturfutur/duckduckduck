# 🦆 DuckDuckDuck

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis](https://img.shields.io/travis/futurfuturfuturfutur/duckduckduck.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/futurfuturfuturfutur/duckduckduck.svg?style=flat-square)](https://packagist.org/packages/futurfuturfuturfutur/duckduckduck)

Laravel package able to auto generate API documentation through tests, without garbage comments inside the code base.

## How it works
If your project has excellent feature testing coverage, it means you already has documentation for this project. You've 
already described available paths, requests body, parameters, content types, response payloads and http codes. You shouldn't 
describe it again by your own just in format of the api documentation you use.The package principle is super simple: when you run tests,
package middleware just grab response and request of the test case and saves it as an api documentation config file, which 
you can use to render.

## Install
1. Install package with `composer require --dev futurfuturfuturfutur/duckduckduck`
2. Publish and setup package with `php artisan duckduckduck:init`. This command will publish duckduckduck.php configuration
file inside the ```config``` folder of Laravel with base configuration of your API documentation.
3. Add TestCase trait inside the phpunit TestCase 
    ``` php
    <?php
    
    namespace Tests;
    
    use Futurfuturfuturfutur\Duckduckduck\Traits\DuckduckduckTestCase;
    use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
    
    abstract class TestCase extends BaseTestCase
    {
        use CreatesApplication;
        use DuckduckduckTestCase;
        ...
    }
    ```

## Usage
### Setup
The package will handle most of the things, but some of them you need to define by yourself:
1. Define base info of the API documentation (name, description, type of documentation, version) inside the `config/duckduckduck.php`
1. Route description and group should be defined as PHPDoc comment of the class of the test (All of them are optional):
    ``` php
    /**
     * @DuckDuckDuckRouteGroup Group name
     * @DuckDuckDuckRouteDescription Description
     */
   class IndexTest extends TestCase
   {
    ```
1. A test case description should be defined as PHPDoc comment of the case method:
    ``` php
    /**
     * @DuckDuckDuckRouteDescription Description of the case
     */
    public function testShow()
    {
    ```
### Run build
Run your tests in API documentation generation mode with `php artisan duckduckduck:generate`. Package will generate documentation
 file inside the root of the app.
 
### API documentation
 - [x] Base info about documentation
 - [x] Grouped paths with descriptions and methods
 - [x] Path, query and body parameters from request
 - [x] Responses for the route with payloads and codes
 - [ ] Parameters validation rules from form request
 
## Contributing
Please see [Contribution](CONTRIBUTING.md) for details.

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.

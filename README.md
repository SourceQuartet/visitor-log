# VisitorLog for Laravel 5
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SourceQuartet/visitor-log/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SourceQuartet/visitor-log/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/SourceQuartet/visitor-log/badges/build.png?b=master)](https://scrutinizer-ci.com/g/SourceQuartet/visitor-log/build-status/master) [![Code Climate](https://codeclimate.com/github/SourceQuartet/visitor-log/badges/gpa.svg)](https://codeclimate.com/github/SourceQuartet/visitor-log)


A K.I.S.S package to log your Visitor for your Laravel 5 apps.

This package allows you to log your visitor into the database with their useragent and has multiples habilities, it can find if an user is currently on your website, if a visitor is a authentificated user and others habilities :+1:

It also includes [jenssegers/laravel-agent](https://github.com/jenssegers/laravel-agent) based from [Mobile-Detect library](https://github.com/serbanghita/Mobile-Detect) to allow you to identify the Useragent of a Visitor.

It is based on the excellent base from [JN-Jones/visitor-log](https://github.com/JN-Jones/visitor-log), completely reworked for Laravel 5 using Repositories pattern and Middleware.

## Installation

```
$ composer require sourcequartet/visitor-log
$ php composer update
```

## Configuration on Laravel 5+
Add providers to `config/app.php`
```php
'providers' => [

    SourceQuartet\VisitorLog\VisitorLogServiceProvider::class,

],
```

Also you can add the Facade for Visitor class :

```php
'aliases' => [

	'Visitor' => SourceQuartet\VisitorLog\VisitorLogFacade::class,

],
```

### Publish migration and package config:

```
$ php artisan vendor:publish --provider="SourceQuartet\VisitorLog\VisitorLogServiceProvider"
```

### Add the Middleware (you must)

Add this line to your `App/Http/Kernel.php`

```php
protected $middleware = [
        \SourceQuartet\VisitorLog\Middleware\VisitorMiddleware::class,
    ];
```

You can also register it to as a route Middleware but as the package can ignore route within his own configuration, it's all you choice, I recommand to not :yum:

## Configuration

 * `onlinetime`: The time (in minutes) while a visitor is still saved
 * `usermodel`: Set this to the Auth Provider you're using:
 	* `Laravel`: The package will use any package that extend Laravel Guard or will use the basic Auth Guard
 	* `Sentinel`: Visitor-Log will try to get the User with Sentinel **-- NOT READY --**
 * `ignore`: This is an array of pages that will be ignored by Visitor-Log. Example "admin/online"

### API and VisitorLog classes

The Visitor class is an interface binded and included into the Service Container as a Singleton :
* `SourceQuartet\VisitorLog\Visitor\Visitor` is the VisitorManager Interface
* `SourceQuartet\VisitorLog\Visitor\VisitorManager` is the direct instance of the VisitorManager, where the logic before sending data to Repository is stored, require `Illuminate\Config\Repository`, `Illuminate\Http\Request` and `Jenssegers\Agent\Agent` as dependencies to be constructed.
* `SourceQuartet\VisitorLog\Contracts\Visitor\VisitorContract` is the VisitorRepository Interface-Contract
* `SourceQuartet\VisitorLog\Visitor\VisitorRepository` is the `SourceQuartet\VisitorLog\VisitorModel` repository handling all the database layer, it requires `SourceQuartet\VisitorLog\VisitorModel` and `Illuminate\Database\DatabaseManager` to be constructed and is bind with his Contract called : `SourceQuartet\VisitorLog\Contracts\Visitor\VisitorContract`
* `SourceQuartet\VisitorLog\VisitorModel` is the Visitor table Model, it manages the logging of the `sid` attributes and is extended by `Illuminate\Database\Eloquent\Model`

## Facade and Visitor class methods
```php
/** Will check whether the user with $id is online and registered as a visitor
 *  return false if the user is not an authentificated User, else, it returns true
 */
Visitor::checkOnline($id);

/** Find the current visitor using his address IP and retriving his unique SID,
 *  The SID is also stored into the session through Illuminate\Http\Request accessor
 */
Visitor::findCurrent();

/** Clear all visitor where the created_at timestamp is inferior at created_at minus config(visitor-log::onlinetime)
 *  Should not be called out of the scope of a custom Middleware.
 */
Visitor::clear($time);

/** loggedIn() - Will return all visitor that are currently authentificated through Auth or Sentinel
 *  guests() - Will return all visitor that are currently not authentificated through Auth or Sentinel
 */
Visitor::loggedIn();
Visitor::guests();

/** Find a visitor by his User id, if the user is online and authentificated, it'll return VisitorModel Collection
 *  If the $id isn't passed as an attribute, it'll throw an SourceQuartet\Exception\InvalidArgumentException, 
 *  If the user corresponding to the $id is not a visitor (offline), it'll return a null
 */
Visitor::findUser($id);

/** Find a visitor by his IP, if the user is online and authentificated, it'll return VisitorModel Collection
 *  If the $ip isn't passed as an attribute/invalid IP, it'll throw an SourceQuartet\Exception\InvalidArgumentException,
 */
Visitor::findByIp($id);

/** isUser() - Check if current Visitor is an Authentificated User, return bool (true if authentificated, false if not)
 *  isGuest() - Opposite method to isUser()
 *  getUseragent() - Get current Visitor stored Useragent
 */
Visitor::isUser();
Visitor::isGuest();
Visitor::getUseragent();

// Fetch all visitor logged into the database.
Visitor::all();

// Should not be used out of the scope of a Custom Middleware of for testing purposes.
Visitor::create(array $attributes);
Visitor::updateOrCreate(array $attributes);

// Find Visitor directly with his SID
Visitor::create($id);

// Should not be used out of the VisitorManager constructor or Custom Middleware
Visitor::setAgentDetector();
```
 
## Using `jenssegers/laravel-agent`

 * `getAgentDetector()` - This method will return an Instance of `Jenssegers\Agent\Agent`

### is('Agent') method :
```php
Visitor::getAgentDetector()->is('Windows'); // Check if UserAgent is Windows
Visitor::getAgentDetector()->is('Firefox'); // Check if UserAgent is Firefox
Visitor::getAgentDetector()->is('iPhone'); // Check if UserAgent is iPhone
Visitor::getAgentDetector()->is('OS X'); // Check if UserAgent is OS X
```
### Magic is-Methods :
```php
Visitor::getAgentDetector()->isAndroidOS();
Visitor::getAgentDetector()->isNexus();
Visitor::getAgentDetector()->isSafari();
Visitor::getAgentDetector()->isMobile();
Visitor::getAgentDetector()->isTablet();
```

### Setting up an UserAgent
Sometime, you may need to foreach or to set manually the Useragent to analyze, so it's quite simple :
```php
$visitors = Visitor::all();
foreach($visitors as $visitor)
{
    Visitor::getAgentDetector()->setAgentToDetector($visitor->useragent);
    Visitor::getAgentDetector()->isAndroidOS();
}
```
Or in the case if you need to load the current Useragent :
```php
// Just leave it empty
Visitor::getAgentDetector()->setAgentToDetector();
Visitor::getAgentDetector()->isAndroidOS();
```php

Or in the case of a single loading
```php
$visitor = Visitor::findUser($id);
Visitor::getAgentDetector()->setAgentToDetector($visitor->useragent);
Visitor::getAgentDetector()->isAndroidOS();
```

### Others functionnality of the AgentDetector using MobileDetect and jenssegers\laravel-agent :

Please check out [jenssenger\laravel-agent docs](https://github.com/jenssegers/laravel-agent/blob/master/README.md) for a more complete documentation on how to use this UserAgent detector, if you want to use inbuild of VisitorLog, just replace `Agent::method()` by `Visitor::getAgentDetector()->method()` it's not simpler, and if you want use directely the Agent Facade, register it as `jenssenger\laravel-agent` is a dependency of this package and so, you just need to follow the installation inscruction to use it out of the scope of this package :+1:

## Visitor Model

The Visitor Model also provides some attributes:
 * `sid`: A random String which is used to identicate the visitor
 * `ip`: The IP of the visitor
 * `page`: The Page where the visitor is
 * `useragent`: The useragent of the visitor
 * `user`: The UserID of the visitor

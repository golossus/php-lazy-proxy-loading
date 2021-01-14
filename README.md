[![Build Status](https://api.travis-ci.org/golossus/php-lazy-proxy-loading.svg?branch=main)](https://api.travis-ci.org/golossus/php-lazy-proxy-loading)
[![codecov](https://codecov.io/gh/golossus/php-lazy-proxy-loading/branch/main/graph/badge.svg?token=6ZFJDNPL4S)](https://codecov.io/gh/golossus/php-lazy-proxy-loading)

<p align="center">
    <a href="https://www.golossus.com" target="_blank">
        <img height="100" src="https://avatars2.githubusercontent.com/u/58183018">
    </a>
</p>

[php-lazy-proxy-loading][1] is a package which tries to ease in the process of loading PHP classes (or services) by
using a lazy strategy. This comes in handy when working with Dependency Injection containers which are in charge of
building the services of many applications.

This package does so by providing a [Proxy Class Factory][2] which can create a Proxy Class for any PHP Class or service.
Real service is only built when the service is really needed, that is at first call of its public methods.

It is specially interesting to combine this library with Laravel framework, given the fact that currently there's no a
bult-in strategy in this framework to provide lazy-loading of services. We started this library in order to solve
Dependency Injection issues we had using Laravel that could be solver very easily by lazy-loading some services.

Installation
------------

```shell
composer require golossus/php-lazy-proxy-loading
```

Usage
-----

Basic usage example:

```php
use Golossus\LazyProxyLoading\ProxyClassFactory;
...
$myClassProxy = ProxyClassFactory::create(\MyClass::class, function () {
    return new \MyClass(/* dependencies */);
}); 
```

It's worth noting in the above example `$myClassProxy` extends `MyClass` type, and it serves as an adapter behaving as
the underlying service, so it can be injected seamlessly as the real service. Final service will be built once we call
any of its public methods by using the callback function passed as second argument of the `ProxyClassFactory::create`
method.

```php
echo $myProxyClass->myClassPrublicMethod(); // MyClass service is created at this point
echo $myProxyClass->myClassPrublicMethod(); // MyClass service is already created at this point, so it's not re-built
```

### Laravel

For Laravel framework, this package provides a special [trait][3] to seamlessly use the proxy factory with its Dependency
Injection container. You can `use` the trait in any Laravel service provider and define any service as `lazy`:

```php
..
use Illuminate\Support\ServiceProvider;
use Golossus\LazyProxyLoading\LazyLoadingTrait;
..

class MainProvider extends ServiceProvider
{
    use LazyLoadingTrait;

    public function register()
    {
        // common laravel service registration, not lazy
        $this->app->singleton(CustomMailingService::class, function () {
                $mailer = $this->app->make(Mailer::class);
                return new CustomMailingService($mailer);
            }
        );
    
        // or same service registration but lazy loaded
        $this->app->singleton(...$this->lazy(CustomMailingService::class, function () {
                $mailer = $this->app->make(Mailer::class);

                return new CustomMailingService($mailer);
            }
        ));
    }
}

```

Community
---------

* Join our [Slack][4] to meet the community and get support.
* Follow us on [GitHub][5].
* Read our [Code of Conduct][6].

Contributing
------------

This is an Open Source project. The Golossus team wants to enable it to be community-driven and open
to [contributors][7]. Take a look at [contributing documentation][8].

Security Issues
---------------

If you discover a security vulnerability, please follow our [disclosure procedure][9].

About Us
--------

This package development is led by the Golossus Team [Leaders][10] and supported by [contributors][7].

[1]: https://github.com/golossus/php-lazy-proxy-loading
[2]: ./lib/ProxyClassFactory.php
[3]: ./lib/LaravelLazyLoadingTrait.php
[4]: https://join.slack.com/t/golossus/shared_invite/zt-db4brnes-M8q1Lw2ouFT5X~gQg69NQQ
[5]: https://github.com/golossus
[6]: ./CODE_OF_CONDUCT.md
[7]: ./CONTRIBUTORS.md
[8]: ./CONTRIBUTING.md
[9]: ./CONTRIBUTING.md#reporting-a-security-issue
[10]: ./CONTRIBUTING.md#leaders

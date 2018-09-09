# Stringy Patterns

A PHP string pattern detection library with multibyte support.

```php
use Stringy\Patterns as P;

// get all patterns
$results_array = P::create('abcdabcdab')->getPatterns();

// get all patterns, include single occurrences
$results_array = P::create('abcdabcdab')->getPatterns(true);
```

* [Why?](#why)
* [Installation](#installation)
* [Other Useful Libraries](#other-useful-libraries)
* [License](#license)


## Why?

Patterns are all around us, perhaps _you_ will find something useful.


## Installation

If you're using Composer to manage dependencies:
```
composer require aternus/stringy-patterns
```

Then, after running `composer update`, you can load the class using Composer's autoloading:

```php
require 'vendor/autoload.php';
```

Otherwise, you can simply require the file directly:

```php
require_once 'vendor/aternus/stringy-patterns/src/Patterns.php';
```

And in either case, I'd suggest using an alias.

```php
use Stringy\Patterns as P;
```

Please note that Stringy Patterns relies on the `mbstring` module for its underlying
multibyte support. If the module is not found, and as long as you've installed
Stringy Patterns using composer, Stringy Patterns will use
[symfony/polyfill-mbstring](https://github.com/symfony/polyfill-mbstring).
For OSX users, it's a default for any version of PHP installed with homebrew.
If compiling PHP from scratch, it can be included with the `--enable-mbstring` flag.


## Other Useful Libraries

* [Stringy](https://github.com/danielstjules/Stringy):
A PHP string manipulation library with multibyte support


## License

Released under the MIT License - see `LICENSE.md` for details.

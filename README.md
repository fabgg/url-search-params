# URLSearchParams Php library to manage query parameters in url.

This is a php library inspired by JavaScript's `URLSearchParams` class.

## Installation

This can also be installed with `composer`.

```sh
$ composer require fabgg/url-search-params
```
> This library follows the PSR-4 convention names for its classes, which means you can easily integrate `url-search-params` classes loading in your own autoloader.

## Getting Started
Basic example of how to use this library

```php
<?php

// Include dependencies installed with composer
require 'vendor/autoload.php';

use Fabgg\UrlSearchParams\URLSearchParams;

$search = new URLSearchParams('?q=github');
$search->merge(['user'=>'fabrice'])
echo $search->toString();

```
The result would render the following string : `?q=github&user=fabrice`

## Usage

Use `URLSearchParams` directly. You can instantiate a new instance of `URLSearchParams` from a string or an array.
```php
<?php
$search = new URLSearchParams('?q=github+web+site&u=yan');
// equivalent to:
$search = new URLSearchParams(['q' => 'github web site', 'u' => 'yan']);

```

### append

```php
$search= new URLSearchParams();
// array
search->append(["id"=> 1]);
```

### appendTo

```php
$search = new URLSearchParams();
// key and value
$search->appendTp("id"=> 1);
// value can be an array
$search->appendTp("id"=> [3,5,7]);
echo (string)$search;
// result is `?id=1&id=3&id=5&id=7`

```

### delete

```php
$search.delete("id");
```

### get

```php
$search.get("id");
// return an array [1, 3, 5, 7]
```

### getAll

```php
$search = new URLSearchParams('?q=github&u=yan');
// all query parameters are
$search.getAll();
// return ['q' => ['github'], 'u' => ['yan']]
$search.getAll("q");
// return ['q' => ['github']] like $search->get('q')

```

### has

```php
$search.has("id");
```

### merge

```php
$search = new URLSearchParams('?q=vegetable&flavour=sweet');
$search->merge('?q=fruits');
echo $search->toString();
// return '?q=vegetable&q=fruits&flavour=sweet'
$search->merge(['flavour' => 'bitter','color'=>'red tomato']);
echo $search->toString();
// return '?q=vegetable&q=fruits&flavour=sweet&flavour=bitter&color=red+tomato'

```

### toString

```php
$search.toString();
// or call __toString()
(string)$search;
```

### keys

```php
$search = new URLSearchParams('?q=github&u=yan');
$search->keys();
// return ['q','u']
```

## LICENSE

MIT license


AADateTime
==========

This aims to be a PHP DateTime/DateInterval polyfill for PHP5.2.

PHP5.2 does not include the [DateInterval][PHP-DateInterval] class. It also does not include a number of useful methods on the DateTime object.

The following methods and features are made available:

## AADateTime

- [`add`][PHP-DateTime-add]
- [`sub`][PHP-DateTime-sub]
- [`diff`][PHP-DateTime-diff]
- [`getTimestamp`][PHP-DateTime-getTimestamp]
- [`setTimestamp`][PHP-DateTime-setTimestamp]
- [`setDate`][PHP-DateTime-setDate] - Behaves like PHP5.3, returning `$self`
- `__sleep`/`__wakeup`

_The API is identical to PHP5.3's_

## DateInterval

- [`__construct`][PHP-DateInterval-__construct]
- _All instance variables_


## Usage

Simply replace all uses of `DateTime` with `AADateTime` in your code.

## Pitfalls

- The following methods are still **not** available:
  `DateInterval::format`, `DateInterval::createFromFormat`

- DateTime format strings of the format "first day of..." or "last day of..." cannot be used

- 


[PHP-DateInterval]: http

[PHP-DateTime-add]: http://www.php.net/manual/en/datetime.add.php
[PHP-DateTime-diff]: http://www.php.net/manual/en/datetime.diff.php
[PHP-DateTime-getTimestamp]: http://www.php.net/manual/en/datetime.gettimestamp.php
[PHP-DateTime-setTimestamp]: http://www.php.net/manual/en/datetime.settimestamp.php
[PHP-DateTime-sub]: http://www.php.net/manual/en/datetime.sub.php
[PHP-DateTime-setDate]: http://www.php.net/manual/en/datetime.setdate.php

[PHP-DateInterval-__construct]: http://www.php.net/manual/en/dateinterval.construct.php
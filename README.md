# DateTai

[![PHP Version](https://img.shields.io/badge/php-%5E8.1.7-blue.svg)](https://img.shields.io/badge/php-%5E8.1.7-blue.svg)
![CI workflow](https://github.com/amaccis/datetai/actions/workflows/ci.yml/badge.svg)


A PHP library including a set of tools for handling the TAI time standard with DateTime and DateTimeImmutable instances.

For a basic knowledge of TAI [read this](https://en.wikipedia.org/wiki/International_Atomic_Time).
For a basic knowledge of TAI's external formats, that is TAI64, TAI64N and TAI64NA, [read this](https://cr.yp.to/libtai/tai64.html).
Finally, for a basic knowledge of leap seconds [read this](https://maia.usno.navy.mil/information/what-is-a-leap-second).

*Since DateTimeInterface does not handle nanoseconds, TAI64N format is partially supported by this library and the values are always rounded to microseconds. 
For the same reason TAI64NA format is not currently supported.*

## Installation

The library is available as a package on [Packagist](http://packagist.org/packages/amaccis/datetai), so you can install it using [Composer](http://getcomposer.org)
```shell
composer require amaccis/datetai
```

## Basic usage

### TAI/UTC conversion

```php
    use Amaccis\DateTai\DateTai;
    use DateTime;
    use DateTimeImmutable;

    $taiDateTime = new DateTime('1992-06-02 08:07:09');
    $utcDateTime = DateTai::convertTaiIntoUtc($taiDateTime);
    var_dump($utcDateTime->format('Y-m-d H:i:s')); // 1992-06-02 08:06:43

    $taiDateTimeImmutable = new DateTimeImmutable('1992-06-02 08:07:09');
    $utcDateTimeImmutable = DateTai::convertTaiIntoUtc($taiDateTimeImmutable);
    var_dump($utcDateTimeImmutable->format('Y-m-d H:i:s')); // 1992-06-02 08:06:43
```

### UTC/TAI conversion

```php
    use Amaccis\DateTai\DateTai;
    use DateTime;
    use DateTimeImmutable;

    $utcDateTime = new DateTime('1992-06-02 08:06:43');
    $taiDateTime = DateTai::convertUtcIntoTai($utcDateTime);
    var_dump($taiDateTime->format('Y-m-d H:i:s')); // 1992-06-02 08:07:09

    $utcDateTimeImmutable = new DateTimeImmutable('1992-06-02 08:06:43');
    $taiDateTimeImmutable = DateTai::convertUtcIntoTai($utcDateTimeImmutable);
    var_dump($taiDateTimeImmutable->format('Y-m-d H:i:s')); // 1992-06-02 08:07:09
```

### Format a DateTimeInterface using an external TAI format

```php
    use Amaccis\DateTai\DateTai;
    use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;
    use DateTime;
    
    $dateTime = new DateTime('1992-06-02 08:07:09');
    $externalTai64Format = DateTai::formatAsExternalTaiFormat($dateTime, ExternalTaiFormatEnum::TAI64);
    var_dump($externalTai64Format); // 400000002a2b2c2d
```
```php
    use Amaccis\DateTai\DateTai;
    use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;
    use DateTimeImmutable;
    
    $dateTimeImmutable = new DateTimeImmutable('1992-06-02 08:07:09.389984500');
    $externalTai64NFormat = DateTai::formatAsExternalTaiFormat($dateTimeImmutable, ExternalTaiFormatEnum::TAI64N);
    var_dump($externalTai64NFormat); // 400000002a2b2c2d173eaf00
```

### Create a DateTimeInterface from a TAI label, formatted using an external TAI format

```php
    use Amaccis\DateTai\DateTai;
    use Amaccis\DateTai\Enum\DateTimeInterfaceEnum;
    use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;

    $externalTai64Format = "400000002a2b2c2d";
    $dateTime = DateTai::createFromExternalTaiFormat(ExternalTaiFormatEnum::TAI64, $externalTai64Format, DateTimeInterfaceEnum::DateTime);
    var_dump($dateTimeImmutable->format('Y-m-d H:i:s')); // 1992-06-02 08:07:09
```
```php
    use Amaccis\DateTai\DateTai;
    use Amaccis\DateTai\Enum\DateTimeInterfaceEnum;
    use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;

    $externalTai64NFormat = "400000002a2b2c2d173eb0f4";
    $dateTimeImmutable = DateTai::createFromExternalTaiFormat(ExternalTaiFormatEnum::TAI64N, $externalTai64NFormat, DateTimeInterfaceEnum::DateTimeImmutable);
    var_dump($dateTimeImmutable->format('Y-m-d H:i:s.u')); // 1992-06-02 08:07:09.389985
```

<?php

namespace Amaccis\DateTai;

use Amaccis\DateTai\Enum\DateTimeInterfaceEnum;
use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;
use DateTimeInterface;

interface DateTaiInterface
{

    public static function convertIntoUtc(DateTimeInterface $dateTimeInterface): DateTimeInterface;

    public static function convertIntoTai(DateTimeInterface $dateTimeInterface): DateTimeInterface;

    public static function formatAsExternalTaiFormat(DateTimeInterface $dateTimeInterface, ExternalTaiFormatEnum $externalTaiFormatEnum): string;

    public static function createFromExternalTaiFormat(ExternalTaiFormatEnum $externalTaiFormatEnum, string $externalTaiFormat, DateTimeInterfaceEnum $dateTimeInterfaceEnum): DateTimeInterface;

}
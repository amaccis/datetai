<?php

namespace Amaccis\DateTai;

use Amaccis\DateTai\Enum\DateTimeInterfaceEnum;
use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;
use Amaccis\DateTai\Enum\TimeStandardEnum;
use Amaccis\DateTai\Tool\TaiTool;
use Amaccis\DateTai\Tool\TimeStandardTool;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;

class DateTai implements DateTaiInterface
{

    /**
     * @param DateTimeInterface $dateTimeInterface
     * @return DateTimeInterface
     */
    public static function convertIntoUtc(DateTimeInterface $dateTimeInterface): DateTimeInterface
    {

        return TimeStandardTool::convert($dateTimeInterface, TimeStandardEnum::UTC);

    }

    /**
     * @param DateTimeInterface $dateTimeInterface
     * @return DateTimeInterface
     */
    public static function convertIntoTai(DateTimeInterface $dateTimeInterface): DateTimeInterface
    {

        return TimeStandardTool::convert($dateTimeInterface, TimeStandardEnum::TAI);

    }

    /**
     * @param DateTimeInterface $dateTimeInterface
     * @param ExternalTaiFormatEnum $externalTaiFormatEnum
     * @return string
     * @throws Exception
     */
    public static function formatAsExternalTaiFormat(DateTimeInterface $dateTimeInterface, ExternalTaiFormatEnum $externalTaiFormatEnum): string
    {

        return TaiTool::dateTimeInterfaceToExternalTaiFormat($dateTimeInterface, $externalTaiFormatEnum);

    }

    /**
     * @param ExternalTaiFormatEnum $externalTaiFormatEnum
     * @param string $externalTaiFormat
     * @param DateTimeInterfaceEnum $dateTimeInterfaceEnum
     * @return DateTimeInterface
     * @throws Exception
     */
    public static function createFromExternalTaiFormat(ExternalTaiFormatEnum $externalTaiFormatEnum, string $externalTaiFormat, DateTimeInterfaceEnum $dateTimeInterfaceEnum): DateTimeInterface
    {

        $unixTimestamp = TaiTool::externalTaiFormatToUnixTimestamp($externalTaiFormatEnum, $externalTaiFormat);
        $datetime = sprintf('@%s', $unixTimestamp);
        return match ($dateTimeInterfaceEnum) {
            DateTimeInterfaceEnum::DateTime => new DateTime($datetime, new DateTimeZone('UTC')),
            DateTimeInterfaceEnum::DateTimeImmutable => new DateTimeImmutable($datetime, new DateTimeZone('UTC'))
        };

    }

}
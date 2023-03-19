<?php

namespace Amaccis\DateTai\Tool;

use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;
use Amaccis\DateTai\Exception\ExternalTaiFormatNotSupportedException;
use Amaccis\DateTai\Exception\WrongExternalTaiFormatException;
use DateTimeInterface;
use Exception;

class TaiTool {

    private const TAI_SECOND_EPOCH = 2**62;

    /**
     * @param ExternalTaiFormatEnum $externalTaiFormatEnum
     * @param string $externalTaiFormat
     * @return string
     * @throws WrongExternalTaiFormatException
     * @throws ExternalTaiFormatNotSupportedException
     */
    public static function externalTaiFormatToUnixTimestamp(ExternalTaiFormatEnum $externalTaiFormatEnum, string $externalTaiFormat): string
    {

        if (!ctype_xdigit($externalTaiFormat)) {
            throw new WrongExternalTaiFormatException();
        }
        $taiLabelNumberOfBytes = strlen($externalTaiFormat)/2;
        $externalTaiFormatNumberOfBytes = match($externalTaiFormatEnum) {
            ExternalTaiFormatEnum::TAI64 => 8,
            ExternalTaiFormatEnum::TAI64N => 12,
            ExternalTaiFormatEnum::TAI64NA => 16
        };
        if ($taiLabelNumberOfBytes !== $externalTaiFormatNumberOfBytes) {
            throw new WrongExternalTaiFormatException();
        }
        return match ($externalTaiFormatEnum) {
            ExternalTaiFormatEnum::TAI64 => self::externalTai64FormatToTaiSecond($externalTaiFormat),
            ExternalTaiFormatEnum::TAI64N => self::externalTai64NFormatToTaiNanosecond($externalTaiFormat),
            ExternalTaiFormatEnum::TAI64NA => throw new ExternalTaiFormatNotSupportedException(),
        };

    }

    /**
     * @param string $tai64NLabel
     * @return string
     */
    private static function externalTai64NFormatToTaiNanosecond(string $tai64NLabel): string {

        $second = intval(self::externalTai64FormatToTaiSecond(substr($tai64NLabel, 0, 16)));
        $nanosecond = intval(hexdec(substr($tai64NLabel, 16)));
        $microsecond = floatval(round($nanosecond/1000)/1000000);
        return number_format($second + $microsecond, 6, '.', '');

    }

    /**
     * @param string $tai64Label
     * @return string
     */
    private static function externalTai64FormatToTaiSecond(string $tai64Label): string
    {

        $second = intval(hexdec($tai64Label));
        if ($second < self::TAI_SECOND_EPOCH) {
            return (string) -(self::TAI_SECOND_EPOCH - $second);
        } else {
            return (string) ($second - self::TAI_SECOND_EPOCH);
        }

    }

    /**
     * @param DateTimeInterface $dateTimeInterface
     * @param ExternalTaiFormatEnum $externalTaiFormatEnum
     * @return string
     * @throws Exception
     */
    public static function dateTimeInterfaceToExternalTaiFormat(DateTimeInterface $dateTimeInterface, ExternalTaiFormatEnum $externalTaiFormatEnum): string
    {

        $format = $dateTimeInterface->format('U.u');
        list($second, $microsecond) = explode('.', $format);
        $nanosecond = $microsecond*1000;
        return match ($externalTaiFormatEnum) {
            ExternalTaiFormatEnum::TAI64 => self::secondToExternalTai64Format(intval($second)),
            ExternalTaiFormatEnum::TAI64N => self::nanosecondToExternalTai64Format(intval($second), intval($nanosecond)),
            ExternalTaiFormatEnum::TAI64NA => throw new ExternalTaiFormatNotSupportedException(),
        };

    }

    /**
     * @param int $second
     * @return string
     */
    private static function secondToExternalTai64Format(int $second): string
    {

        return dechex(self::TAI_SECOND_EPOCH + $second);

    }

    /**
     * @param int $second
     * @param int $nanosecond
     * @return string
     */
    private static function nanosecondToExternalTai64Format(int $second, int $nanosecond): string
    {

        $second = self::secondToExternalTai64Format($second);
        $nanosecond = dechex($nanosecond);
        return $second.$nanosecond;

    }

}
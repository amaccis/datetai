<?php

namespace Amaccis\DateTai\Tool;

use Amaccis\DateTai\Enum\TimeStandardEnum;
use DateTimeInterface;

class TimeStandardTool
{

    // source: https://maia.usno.navy.mil/ser7/tai-utc.dat
    public const LEAP_SECONDS = [
        '1972-01-01' => '10.0',
        '1972-07-01' => '11.0',
        '1973-01-01' => '12.0',
        '1974-01-01' => '13.0',
        '1975-01-01' => '14.0',
        '1976-01-01' => '15.0',
        '1977-01-01' => '16.0',
        '1978-01-01' => '17.0',
        '1979-01-01' => '18.0',
        '1980-01-01' => '19.0',
        '1981-07-01' => '20.0',
        '1982-07-01' => '21.0',
        '1983-07-01' => '22.0',
        '1985-07-01' => '23.0',
        '1988-01-01' => '24.0',
        '1990-01-01' => '25.0',
        '1991-01-01' => '26.0',
        '1992-07-01' => '27.0',
        '1993-07-01' => '28.0',
        '1994-07-01' => '29.0',
        '1996-01-01' => '30.0',
        '1997-07-01' => '31.0',
        '1999-01-01' => '32.0',
        '2006-01-01' => '33.0',
        '2009-01-01' => '34.0',
        '2012-07-01' => '35.0',
        '2015-07-01' => '36.0',
        '2017-01-01' => '37.0',
        // the future leap seconds entries should be added here
    ];

    /**
     * @param DateTimeInterface $dateTimeInterface
     * @param TimeStandardEnum $timeStandardEnum
     * @return DateTimeInterface
     */
    public static function convert(DateTimeInterface $dateTimeInterface, TimeStandardEnum $timeStandardEnum): DateTimeInterface
    {

        $adjustmentString = match($timeStandardEnum) {
            TimeStandardEnum::UTC => '-%s seconds',
            TimeStandardEnum::TAI => '+%s seconds'
        };
        return self::adjust($dateTimeInterface, $adjustmentString);

    }

    /**
     * @param DateTimeInterface $dateTimeInterface
     * @param string $adjustmentString
     * @return DateTimeInterface
     */
    private static function adjust(DateTimeInterface $dateTimeInterface, string $adjustmentString) : DateTimeInterface
    {

        $leapSeconds = self::LEAP_SECONDS;
        $leadSecondsApplicationStartingDate = array_keys($leapSeconds)[0];
        $dateToAdjust = $dateTimeInterface->format('Y-m-d');
        if ($dateToAdjust >= $leadSecondsApplicationStartingDate) {
            if (!array_key_exists($dateToAdjust, $leapSeconds)) {
                $leapSeconds[$dateToAdjust] = '0';
                ksort($leapSeconds);
                $keys = array_keys($leapSeconds);
                $key = (array_search($dateTimeInterface->format('Y-m-d'), $keys, true) - 1);
                $leapSecondsValue = $leapSeconds[$keys[$key]];
            } else {
                $leapSecondsValue = $leapSeconds[$dateToAdjust];
            }
            $seconds = number_format($leapSecondsValue, 0, '.', '');
            $modify = sprintf($adjustmentString, $seconds);
            return $dateTimeInterface->modify($modify);
        }
        return $dateTimeInterface;

    }

}
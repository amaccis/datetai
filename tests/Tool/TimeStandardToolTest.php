<?php

namespace Amaccis\DateTai\Tests\Tool;

use Amaccis\DateTai\DateTai;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class TimeStandardToolTest extends TestCase
{

    /**
     * @dataProvider datetimeProvider
     * @param string $taiDatetime
     * @param string $utcDatetime
     * @return void
     * @throws Exception
     */
    public function testConvertTaiDateTimeIntoUtcDateTime(string $taiDatetime, string $utcDatetime): void
    {

        $taiDateTime = new DateTime($taiDatetime);
        $utcDateTime = DateTai::convertTaiIntoUtc($taiDateTime);
        $this->assertInstanceOf(DateTime::class, $utcDateTime);
        $this->assertEquals($utcDatetime, $utcDateTime->format('Y-m-d H:i:s'));

    }

    /**
     * @dataProvider datetimeProvider
     * @param string $taiDatetime
     * @param string $utcDatetime
     * @return void
     * @throws Exception
     */
    public function testConvertTaiDateTimeImmutableIntoUtcDateTimeImmutable(string $taiDatetime, string $utcDatetime): void
    {

        $taiDateTimeImmutable = new DateTimeImmutable($taiDatetime);
        $utcDateTimeImmutable = DateTai::convertTaiIntoUtc($taiDateTimeImmutable);
        $this->assertInstanceOf(DateTimeImmutable::class, $utcDateTimeImmutable);
        $this->assertEquals($utcDatetime, $utcDateTimeImmutable->format('Y-m-d H:i:s'));

    }

    /**
     * @dataProvider datetimeProvider
     * @param string $taiDatetime
     * @param string $utcDatetime
     * @return void
     * @throws Exception
     */
    public function testConvertUtcDateTimeIntoTaiDateTime(string $taiDatetime, string $utcDatetime): void
    {

        $utcDateTime = new DateTime($utcDatetime);
        $taiDateTime = DateTai::convertUtcIntoTai($utcDateTime);
        $this->assertInstanceOf(DateTime::class, $taiDateTime);
        $this->assertEquals($taiDatetime, $taiDateTime->format('Y-m-d H:i:s'));

    }

    /**
     * @dataProvider datetimeProvider
     * @param string $taiDatetime
     * @param string $utcDatetime
     * @return void
     * @throws Exception
     */
    public function testConvertUtcDateTimeImmutableIntoTaiDateTimeImmutable(string $taiDatetime, string $utcDatetime): void
    {

        $utcDateTimeImmutable = new DateTimeImmutable($utcDatetime);
        $taiDateTimeImmutable = DateTai::convertUtcIntoTai($utcDateTimeImmutable);
        $this->assertInstanceOf(DateTimeImmutable::class, $taiDateTimeImmutable);
        $this->assertEquals($taiDatetime, $taiDateTimeImmutable->format('Y-m-d H:i:s'));

    }

    /**
     * @return array[]
     */
    public static function datetimeProvider(): array
    {

        return [
            [
                '1969-12-31 23:59:59',
                '1969-12-31 23:59:59'
            ],
            [
                '1970-01-01 00:00:00',
                '1970-01-01 00:00:00'
            ],
            [
                '1972-01-01 00:00:10',
                '1972-01-01 00:00:00'
            ],
            [
                '1992-06-02 08:07:09',
                '1992-06-02 08:06:43'
            ]
        ];

    }
}
<?php

namespace Amaccis\DateTai\Tests;

use Amaccis\DateTai\DateTai;
use Amaccis\DateTai\Enum\DateTimeInterfaceEnum;
use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class DateTaiTest extends TestCase
{

    /**
     * @dataProvider tai64LabelProvider
     * @param string $externalTai64Format
     * @param string $datetime
     * @return void
     * @throws Exception
     */
    public function testCreateDateTimeFromExternalTai64Format(string $externalTai64Format, string $datetime): void
    {

        $dateTime = DateTai::createFromExternalTaiFormat(ExternalTaiFormatEnum::TAI64, $externalTai64Format, DateTimeInterfaceEnum::DateTime);
        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertEquals($datetime, $dateTime->format('Y-m-d H:i:s'));

    }

    /**
     * @dataProvider tai64LabelProvider
     * @param string $externalTai64Format
     * @param string $datetime
     * @return void
     * @throws Exception
     */
    public function testCreateDateTimeImmutableFromExternalTai64Format(string $externalTai64Format, string $datetime): void
    {

        $dateTimeImmutable = DateTai::createFromExternalTaiFormat(ExternalTaiFormatEnum::TAI64, $externalTai64Format, DateTimeInterfaceEnum::DateTimeImmutable);
        $this->assertInstanceOf(DateTimeImmutable::class, $dateTimeImmutable);
        $this->assertEquals($datetime, $dateTimeImmutable->format('Y-m-d H:i:s'));

    }

    /**
     * @dataProvider tai64NLabelProvider
     * @param string $externalTai64NFormat
     * @param string $datetime
     * @param string $externalTai64NFormatRounded
     * @param string $datetimeRounded
     * @return void
     * @throws Exception
     */
    public function testCreateDateTimeFromExternalTai64NFormat(string $externalTai64NFormat, string $datetime, string $externalTai64NFormatRounded, string $datetimeRounded): void
    {

        $dateTime = DateTai::createFromExternalTaiFormat(ExternalTaiFormatEnum::TAI64N, $externalTai64NFormat, DateTimeInterfaceEnum::DateTime);
        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertNotEquals($datetime, $dateTime->format('Y-m-d H:i:s.u'));
        $this->assertEquals($datetimeRounded, $dateTime->format('Y-m-d H:i:s.u'));

    }

    /**
     * @dataProvider tai64NLabelProvider
     * @param string $externalTai64NFormat
     * @param string $datetime
     * @param string $externalTai64NFormatRounded
     * @param string $datetimeRounded
     * @return void
     * @throws Exception
     */
    public function testCreateDateTimeImmutableFromExternalTai64NFormat(string $externalTai64NFormat, string $datetime, string $externalTai64NFormatRounded, string $datetimeRounded): void
    {

        $dateTimeImmutable = DateTai::createFromExternalTaiFormat(ExternalTaiFormatEnum::TAI64N, $externalTai64NFormat, DateTimeInterfaceEnum::DateTimeImmutable);
        $this->assertInstanceOf(DateTimeImmutable::class, $dateTimeImmutable);
        $this->assertNotEquals($datetime, $dateTimeImmutable->format('Y-m-d H:i:s.u'));
        $this->assertEquals($datetimeRounded, $dateTimeImmutable->format('Y-m-d H:i:s.u'));

    }

    /**
     * @dataProvider tai64LabelProvider
     * @param string $externalTai64Format
     * @param string $datetime
     * @return void
     * @throws Exception
     */
    public function testFormatDateTimeAsExternalTai64Format(string $externalTai64Format, string $datetime): void
    {

        $dateTime = new DateTime($datetime);
        $actualExternalTai64Format = DateTai::formatAsExternalTaiFormat($dateTime, ExternalTaiFormatEnum::TAI64);
        $this->assertEquals($actualExternalTai64Format, $externalTai64Format);

    }

    /**
     * @dataProvider tai64LabelProvider
     * @param string $externalTai64Format
     * @param string $datetime
     * @return void
     * @throws Exception
     */
    public function testFormatDateTimeImmutableAsExternalTai64Format(string $externalTai64Format, string $datetime): void
    {

        $dateTimeImmutable = new DateTimeImmutable($datetime);
        $actualExternalTai64Format = DateTai::formatAsExternalTaiFormat($dateTimeImmutable, ExternalTaiFormatEnum::TAI64);
        $this->assertEquals($actualExternalTai64Format, $externalTai64Format);

    }

    /**
     * @dataProvider tai64NLabelProvider
     * @param string $externalTai64NFormat
     * @param string $datetime
     * @param string $externalTai64NFormatRounded
     * @return void
     * @throws Exception
     */
    public function testFormatDateTimeAsExternalTai64NFormat(string $externalTai64NFormat, string $datetime, string $externalTai64NFormatRounded): void
    {

        $dateTime = new DateTime($datetime);
        $actualExternalTai64Format = DateTai::formatAsExternalTaiFormat($dateTime, ExternalTaiFormatEnum::TAI64N);
        $this->assertNotEquals($actualExternalTai64Format, $externalTai64NFormat);
        $this->assertEquals($actualExternalTai64Format, $externalTai64NFormatRounded);

    }

    /**
     * @dataProvider tai64NLabelProvider
     * @param string $externalTai64NFormat
     * @param string $datetime
     * @param string $externalTai64NFormatRounded
     * @return void
     * @throws Exception
     */
    public function testFormatDateTimeImmutableAsExternalTai64NFormat(string $externalTai64NFormat, string $datetime, string $externalTai64NFormatRounded): void
    {

        $dateTimeImmutable = new DateTimeImmutable($datetime);
        $actualExternalTai64Format = DateTai::formatAsExternalTaiFormat($dateTimeImmutable, ExternalTaiFormatEnum::TAI64N);
        $this->assertNotEquals($actualExternalTai64Format, $externalTai64NFormat);
        $this->assertEquals($actualExternalTai64Format, $externalTai64NFormatRounded);

    }

    /**
     * @return array[]
     */
    public static function tai64LabelProvider(): array
    {

        return [
            [
                '3fffffffffffffff',
                '1969-12-31 23:59:59',
            ],
            [
                '4000000000000000',
                '1970-01-01 00:00:00',
            ],
            [
                '4000000003c2670a',
                '1972-01-01 00:00:10',
            ],
            [
                '400000002a2b2c2d',
                '1992-06-02 08:07:09',
            ]
        ];
    }

    /**
     * @return array[]
     */
    public static function tai64NLabelProvider(): array
    {
        return [
            [
                '3fffffffffffffff173eb0f4',
                '1969-12-31 23:59:59.389984500',
                '3fffffffffffffff173eaf00',
                '1969-12-31 23:59:59.389985',
            ],
            [
                '4000000052a82012173eb0f4',
                '2013-12-11 08:19:30.389984500',
                '4000000052a82012173eaf00',
                '2013-12-11 08:19:30.389985',
            ],
            [
                '4000000003c2670a173eb0f4',
                '1972-01-01 00:00:10.389984500',
                '4000000003c2670a173eaf00',
                '1972-01-01 00:00:10.389985',
            ],
            [
                '400000002a2b2c2d173eb0f4',
                '1992-06-02 08:07:09.389984500',
                '400000002a2b2c2d173eaf00',
                '1992-06-02 08:07:09.389985',
            ]
        ];

    }

}
<?php

namespace Amaccis\DateTai\Tests\Tool;

use Amaccis\DateTai\Enum\ExternalTaiFormatEnum;
use Amaccis\DateTai\Exception\ExternalTaiFormatNotSupportedException;
use Amaccis\DateTai\Exception\WrongExternalTaiFormatException;
use Amaccis\DateTai\Tool\TaiTool;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class TaiToolTest extends TestCase
{

    /**
     * @return void
     * @throws WrongExternalTaiFormatException
     * @throws ExternalTaiFormatNotSupportedException
     */
    public function testExternalTaiFormatToUnixTimestampExternalTaiFormatNotHexadecimal(): void
    {

        $this->expectException(WrongExternalTaiFormatException::class);
        $wrongExternalTai64Format = "The answer to life, the universe, and everything.";
        TaiTool::externalTaiFormatToUnixTimestamp(ExternalTaiFormatEnum::TAI64, $wrongExternalTai64Format);

    }

    /**
     * @dataProvider wrongExternalTaiLabelProvider
     * @param ExternalTaiFormatEnum $externalTaiFormatEnum
     * @param string $wrongExternalTaiFormat
     * @return void
     * @throws ExternalTaiFormatNotSupportedException
     * @throws WrongExternalTaiFormatException
     */
    public function testExternalTaiFormatToUnixTimestampExternalTaiFormatWrongBytesNumber(ExternalTaiFormatEnum $externalTaiFormatEnum, string $wrongExternalTaiFormat): void
    {

        $this->expectException(WrongExternalTaiFormatException::class);
        TaiTool::externalTaiFormatToUnixTimestamp(ExternalTaiFormatEnum::TAI64, $wrongExternalTaiFormat);

    }

    /**
     * @dataProvider tai64UnixTimestampProvider
     * @param string $externalTai64Format
     * @param string $unixTimestamp
     * @return void
     * @throws Exception
     */
    public function testExternalTaiFormatToUnixTimestamp(string $externalTai64Format, string $unixTimestamp): void
    {

        $actualUnixTimestamp = TaiTool::externalTaiFormatToUnixTimestamp(ExternalTaiFormatEnum::TAI64, $externalTai64Format);
        $this->assertEquals($unixTimestamp, $actualUnixTimestamp);

    }

    /**
     * @dataProvider tai64LabelProvider
     * @param string $externalTai64Format
     * @param string $datetime
     * @return void
     * @throws Exception
     */
    public function testDateTimeToExternalTai64Format(string $externalTai64Format, string $datetime): void
    {

        $dateTime = new DateTime($datetime);
        $actualExternalTai64Format = TaiTool::dateTimeInterfaceToExternalTaiFormat($dateTime, ExternalTaiFormatEnum::TAI64);
        $this->assertEquals($externalTai64Format, $actualExternalTai64Format);

    }

    /**
     * @dataProvider tai64LabelProvider
     * @param string $externalTai64Format
     * @param string $datetime
     * @return void
     * @throws Exception
     */
    public function testDateTimeImmutableToExternalTai64Format(string $externalTai64Format, string $datetime): void
    {

        $dateTime = new DateTimeImmutable($datetime);
        $actualExternalTai64Format = TaiTool::dateTimeInterfaceToExternalTaiFormat($dateTime, ExternalTaiFormatEnum::TAI64);
        $this->assertEquals($externalTai64Format, $actualExternalTai64Format);

    }

    /**
     * @dataProvider tai64NLabelProvider
     * @param string $externalTai64NFormat
     * @param string $externalTai64NFormatRounded
     * @param string $datetime
     * @return void
     * @throws Exception
     */
    public function testDateTimeToExternalTai64NFormat(string $externalTai64NFormat, string $externalTai64NFormatRounded, string $datetime): void
    {

        $dateTime = new DateTime($datetime);
        $actualExternalTai64NFormat = TaiTool::dateTimeInterfaceToExternalTaiFormat($dateTime, ExternalTaiFormatEnum::TAI64N);
        $this->assertNotEquals($externalTai64NFormat, $actualExternalTai64NFormat);
        $this->assertEquals($externalTai64NFormatRounded, $actualExternalTai64NFormat);

    }

    /**
     * @dataProvider tai64NLabelProvider
     * @param string $externalTai64NFormat
     * @param string $externalTai64NFormatRounded
     * @param string $datetime
     * @return void
     * @throws Exception
     */
    public function testDateTimeImmutableToExternalTai64NFormat(string $externalTai64NFormat, string $externalTai64NFormatRounded, string $datetime): void
    {

        $dateTime = new DateTimeImmutable($datetime);
        $actualExternalTai64NFormat = TaiTool::dateTimeInterfaceToExternalTaiFormat($dateTime, ExternalTaiFormatEnum::TAI64N);
        $this->assertNotEquals($externalTai64NFormat, $actualExternalTai64NFormat);
        $this->assertEquals($externalTai64NFormatRounded, $actualExternalTai64NFormat);

    }

    /**
     * @return array[]
     */
    public static function wrongExternalTaiLabelProvider(): array
    {

        return [
            [
                ExternalTaiFormatEnum::TAI64,
                '2A'
            ],
            [
                ExternalTaiFormatEnum::TAI64N,
                '2A'
            ],
            [
                ExternalTaiFormatEnum::TAI64NA,
                '2A'
            ]
        ];

    }

    /**
     * @return array[]
     */
    public static function tai64UnixTimestampProvider(): array
    {

        return [
            [
                '3fffffffffffffff',
                '-1'
            ],
            [
                '4000000000000000',
                '0',
            ],
            [
                '4000000003c2670a',
                '63072010',
            ],
            [
                '400000002a2b2c2d',
                '707472429',
            ]
        ];

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
                '3fffffffffffffff173eaf00',
                '1969-12-31 23:59:59.389984500',
            ],
            [
                '4000000052a82012173eb0f4',
                '4000000052a82012173eaf00',
                '2013-12-11 08:19:30.389984500',
            ],
            [
                '4000000003c2670a173eb0f4',
                '4000000003c2670a173eaf00',
                '1972-01-01 00:00:10.389984500',
            ],
            [
                '400000002a2b2c2d173eb0f4',
                '400000002a2b2c2d173eaf00',
                '1992-06-02 08:07:09.389984500',
            ]
        ];

    }

}
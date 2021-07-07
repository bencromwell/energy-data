<?php

namespace Energy;

use DateTimeImmutable;
use DateTimeInterface;

class SmartMeters
{
    // final meter readings from before the smart meter install were 30971 and 6248 on 9/4/21
    const ELECTRICITY_FINAL = 30971;
    const GAS_FINAL = 6248;
    const FINAL_DATE = '2021-04-09';

    public static function changeoverDate(): DateTimeInterface
    {
        return DateTimeImmutable::createFromFormat('Y-m-d', self::FINAL_DATE);
    }

    public static function addElectric(int $reading): int
    {
        return $reading + self::ELECTRICITY_FINAL;
    }

    public static function addGas(int $reading): int
    {
        return $reading + self::GAS_FINAL;
    }

    public static function subtractElectric(int $reading): int
    {
        return $reading - self::ELECTRICITY_FINAL;
    }

    public static function subtractGas(int $reading): int
    {
        return $reading - self::GAS_FINAL;
    }

    public static function isSmartMeter(DateTimeInterface $dateTime): bool
    {
        return $dateTime->getTimestamp() >= self::changeoverDate()->getTimestamp();
    }
}

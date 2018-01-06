<?php

namespace Energy;

use Carbon\Carbon;

class MonthlyReadingEntity
{

    const STANDARD_MONTH_DAYS = 30;

    /** @var float */
    protected $reading;

    /** @var Carbon */
    protected $dateTime;

    /**
     * MonthlyReadingEntity constructor.
     *
     * @param float  $reading
     * @param Carbon $dateTime
     */
    public function __construct($reading, Carbon $dateTime)
    {
        $this->reading  = $reading;
        $this->dateTime = $dateTime;
    }

    /**
     * @return int
     */
    public function getJsTimestamp()
    {
        return $this->dateTime->getTimestamp() * 1000;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->dateTime->format('Y-m');
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->dateTime->format('Y');
    }

    /**
     * @param bool $raw - raw reading (float) or processed (rounded to an int)?
     *
     * @return int|float
     */
    public function getReading($raw = false)
    {
        return $raw ? $this->reading : (int) round($this->reading);
    }

    /**
     * @param bool $raw - raw reading (float) or processed (rounded to an int)?
     *
     * @return int|float - reading standardised to a 30 day month
     */
    public function getStandardisedReading($raw = false)
    {
        $readingPerDay = $this->reading / $this->dateTime->daysInMonth;

        $resultingReading = $readingPerDay * self::STANDARD_MONTH_DAYS;

        return $raw ? $resultingReading : (int) round($resultingReading);
    }

    /**
     * @return Carbon
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

}

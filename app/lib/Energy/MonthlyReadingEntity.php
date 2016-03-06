<?php

namespace Energy;

use Carbon\Carbon;

class MonthlyReadingEntity
{

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
     * @param bool $raw - raw reading (float) or processed (rounded to an int)?
     *
     * @return int|float
     */
    public function getReading($raw = false)
    {
        return $raw ? $this->reading : (int) round($this->reading);
    }

    /**
     * @return Carbon
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

}

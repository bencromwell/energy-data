<?php

namespace Energy\Etl;

use DateTime;

class MonthlyReading implements IMonthlyReading
{

    protected $kwh;
    protected $date;

    public function __construct($kwh, DateTime $date)
    {
        $this->kwh = $kwh;
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getKwh()
    {
        return $this->kwh;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

}

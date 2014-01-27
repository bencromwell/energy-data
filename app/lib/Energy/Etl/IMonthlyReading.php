<?php

namespace Energy\Etl;

use DateTime;

interface IMonthlyReading
{

    /**
     * @return int
     */
    public function getKwh();

    /**
     * @return DateTime
     */
    public function getDate();

}

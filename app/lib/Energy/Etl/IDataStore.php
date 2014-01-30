<?php

namespace Energy\Etl;

use DateTime;

interface IDataStore
{

    const TYPE_ELECTRICITY = 1;
    const TYPE_GAS         = 2;

    /**
     * @param DateTime $month
     *
     * @return IMonthlyReading
     */
    public function getFirstReadingForMonth(DateTime $month);

    /**
     * @param DateTime $month
     * @return IMonthlyReading
     */
    public function getLastReadingForMonth(DateTime $month);

    /**
     * The logic should perform an upsert on the data store - we only want one row for each month's data
     *
     * The implementation should be able to set itself up for gas or electricity. The MonthlyAggregator doesn't care.
     *
     * @param DateTime $month
     * @param int      $kwhPerDay
     */
    public function saveAggregatedMonthlyResult(DateTime $month, $kwhPerDay);

}

<?php

namespace Energy\Etl;

use DateTime;

/*
 * This is intended to be run as a cron, daily.
 *
 * gas/elec in same format in DB
 *
 * let's assume we're gonna run this smegger daily, with an option to manually specify the month
 *
 * Get month (Input or month now)
 *
 * Get first and last reading for given month, ordered by date
 *
 * If we don't have both readings, exit
 *
 * Record the overall kWh divided by the number of days between the recordings
 *
 * Later we can construct a CostResult object to display the prices
 *
 */
class MonthlyAggregator
{

    /** @var IDataStore */
    protected $store;

    /**
     * @param IDataStore $store
     */
    public function __construct(IDataStore $store)
    {
        $this->store = $store;
    }

    /**
     * @param DateTime $month
     */
    public function run(DateTime $month)
    {
        $first = $this->store->getFirstReadingForMonth($month);
        $last  = $this->store->getLastReadingForMonth($month);

        $kwh = $last->getKwh() - $first->getKwh();

        $dateDiff = $last->getDate()->diff($first->getDate());

        if ($dateDiff->days > 0) {
            $kwhPerDay = $kwh / $dateDiff->days;
        } else {
            $kwhPerDay = $kwh;
        }

        $this->store->saveAggregatedMonthlyResult($month, $kwhPerDay);
    }

}

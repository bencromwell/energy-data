<?php

namespace Energy\Etl;

use DateTime;

class MysqlStore implements IDataStore
{

    protected $type;
    protected $table;

    public function __construct($type)
    {
        $this->type = $type;

        $this->table = $type === IDataStore::TYPE_ELECTRICITY ? 'f_electricity' : 'f_gas';
    }

    /**
     * @param DateTime $month
     *
     * @return IMonthlyReading
     */
    public function getFirstReadingForMonth(DateTime $month)
    {
        $q = \DB::getPdo()->query('SELECT `kwh`, `date` FROM ' . $this->table . ' WHERE MONTH(`date`) = :month AND YEAR(`date`) = :year ORDER BY date ASC LIMIT 1');

        $m = $month->format('m');
        $y = $month->format('Y');

        $q->bindParam(':month', $m);
        $q->bindParam(':year', $y);

        $q->execute();

        $r = $q->fetch(\PDO::FETCH_ASSOC);

        return new MonthlyReading($r['kwh'], DateTime::createFromFormat('Y-m-d', $r['date']));
    }

    /**
     * @param DateTime $month
     *
     * @return IMonthlyReading
     */
    public function getLastReadingForMonth(DateTime $month)
    {
        $q = \DB::getPdo()->prepare('SELECT `kwh`, `date` FROM ' . $this->table . ' WHERE MONTH(`date`) = :month AND YEAR(`date`) = :year ORDER BY date DESC LIMIT 1');

        $m = $month->format('m');
        $y = $month->format('Y');

        $q->bindParam(':month', $m);
        $q->bindParam(':year', $y);

        $q->execute();

        $r = $q->fetch(\PDO::FETCH_ASSOC);

        return new MonthlyReading($r['kwh'], DateTime::createFromFormat('Y-m-d', $r['date']));
    }

    /**
     * The logic should perform an upsert on the data store - we only want one row for each month's data
     *
     * The implementation should be able to set itself up for gas or electricity. The MonthlyAggregator doesn't care.
     *
     * @param DateTime $month
     * @param int      $kwhPerDay
     */
    public function saveAggregatedMonthlyResult(DateTime $month, $kwhPerDay)
    {
        $s = \DB::getPdo()->prepare("INSERT INTO `monthly` (`type`, `month`, `kwh`) VALUES (:type, :month, :kwh) ON DUPLICATE KEY UPDATE `kwh` = :kwh2");

        $m = $month->format('Y-m');
        $kwhPerDay = (int) $kwhPerDay;

        $s->bindParam(':type', $this->type);
        $s->bindParam(':month', $m);
        $s->bindParam(':kwh', $kwhPerDay);
        $s->bindParam(':kwh2', $kwhPerDay);

        $s->execute();
    }

}

<?php

namespace Energy;

class CostResult
{

    const PENCE_PER_POUND = 100;

    private $_kwh;
    private $_cost;
    private $_days;

    /**
     * @param int   $kwh
     * @param int   $cost (in pence)
     * @param int   $days
     */
    public function __construct($kwh, $cost, $days)
    {
        $this->_kwh = $kwh;
        $this->_cost = $cost;
        $this->_days = $days;
    }

    private function getCostPerNumDays($numDays)
    {
        $costPerDay = $this->_cost / $this->_days;
        return $costPerDay * $numDays / self::PENCE_PER_POUND;
    }

    /**
     * Returns cost in pounds (or dollars, doesn't really matter as long as 100 units to a larger unit)
     *
     * @return float
     */
    public function getCost()
    {
        return $this->_cost / self::PENCE_PER_POUND;
    }

    /**
     * Returns cost in pounds (or dollars, doesn't really matter as long as 100 units to a larger unit)
     *
     * @return float
     */
    public function getDailyCost()
    {
        return $this->getCostPerNumDays(1);
    }

    /**
     * Returns cost in pounds (or dollars, doesn't really matter as long as 100 units to a larger unit)
     *
     * @return float
     */
    public function getWeeklyCost()
    {
        return $this->getCostPerNumDays(7);
    }

    /**
     * Returns cost in pounds (or dollars, doesn't really matter as long as 100 units to a larger unit)
     *
     * @return float
     */
    public function getMonthlyCost()
    {
        return $this->getCostPerNumDays(30);
    }

    /**
     * Returns cost in pounds (or dollars, doesn't really matter as long as 100 units to a larger unit)
     *
     * @return float
     */
    public function getAnnualCost()
    {
        return $this->getCostPerNumDays(365);
    }

    public function getMonthlyUsage()
    {
        return round($this->_kwh / $this->_days * 30, 2);
    }

    /**
     * @return int
     */
    public function getDays()
    {
        return $this->_days;
    }

    /**
     * @return int
     */
    public function getKwh()
    {
        return $this->_kwh;
    }

}

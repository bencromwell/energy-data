<?php

namespace Energy;

use DateTime;
use DateInterval;

abstract class CostCalculator
{

    /**
     * @param ICalculable $model1
     * @param ICalculable $model2
     * @param IPrices     $prices
     *
     * @return CostResult
     */
    public function calculate($model1, $model2, $prices)
    {
        $kwh = $model1->getKwh() - $model2->getKwh();

        $day0 = DateTime::createFromFormat('Y-m-d', $model2->getDate());
        $day1 = DateTime::createFromFormat('Y-m-d', $model1->getDate());

        /** @var $di DateInterval */
        $di = date_diff($day0, $day1);

        $days = $di->days;

        $cost = $this->getCost($prices, $days, $kwh);

        return new CostResult($kwh, $cost, $days);
    }

    /**
     * @param IPrices $prices
     * @param int     $days
     * @param int     $kwh
     *
     * @return int cost in pence
     */
    abstract protected function getCost(IPrices $prices, $days, $kwh);

}

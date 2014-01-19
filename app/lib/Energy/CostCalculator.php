<?php

namespace Energy;

use DateTime;
use DateInterval;

class CostCalculator
{

    const TYPE_GAS = 0;
    const TYPE_ELECTRICITY = 1;

    private $_type;

    public function __construct($type)
    {
        $this->_type = $type;
    }

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

        if ($this->_type === self::TYPE_ELECTRICITY) {
            $cost = ($prices->getStandingElectricity() * $days) + ($prices->getElectricityKwh() * $kwh);
        } else {
            $cost = ($prices->getStandingGas() * $days) + ($prices->getGasKwh() * $kwh);
        }

        return new CostResult($kwh, $cost, $days);
    }

}

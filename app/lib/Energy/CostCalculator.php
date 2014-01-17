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

    function calculate($model1, $model2, $prices)
    {
        $kwh = $model1->kwh - $model2->kwh;

        $day0 = DateTime::createFromFormat('Y-m-d', $model2->date);
        $day1 = DateTime::createFromFormat('Y-m-d', $model1->date);

        /** @var $di DateInterval */
        $di = date_diff($day0, $day1);

        $days = $di->days;

        if ($this->_type === self::TYPE_ELECTRICITY) {
            $cost = ($prices->electricity_standing * $days) + ($prices->electricity_kwh * $kwh);
        } else {
            $cost = ($prices->gas_standing * $days) + ($prices->gas_kwh * $kwh);
        }

        return new CostResult($kwh, $cost, $days);
    }

}

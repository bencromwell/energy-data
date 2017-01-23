<?php

namespace Energy;

class GasCostCalculator extends CostCalculator
{
    protected function getCost(IPrices $prices, $days, $kwh)
    {
        return ($prices->getStandingGas() * $days) + ($prices->getGasKwh() * $kwh);
    }
}

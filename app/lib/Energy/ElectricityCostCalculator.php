<?php

namespace Energy;

class ElectricityCostCalculator extends CostCalculator
{
    protected function getCost(IPrices $prices, $days, $kwh)
    {
        return ($prices->getStandingElectricity() * $days) + ($prices->getElectricityKwh() * $kwh);
    }
}

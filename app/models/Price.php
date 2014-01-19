<?php

/**
 * @property float $gas_standing
 * @property float $gas_kwh
 * @property float $electricity_standing
 * @property float $electricity_kwh
 */
class Price extends Eloquent implements \Energy\IPrices
{

    protected $table = 'prices';

    public $timestamps = false;

    public function getStandingElectricity()
    {
        return $this->electricity_standing;
    }

    public function getStandingGas()
    {
        return $this->gas_standing;
    }

    public function getElectricityKwh()
    {
        return $this->electricity_kwh;
    }

    public function getGasKwh()
    {
        return $this->gas_kwh;
    }

}

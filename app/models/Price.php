<?php

/**
 * @property float $gas_standing
 * @property float $gas_kwh
 * @property float $electricity_standing
 * @property float $electricity_kwh
 */
class Price extends Eloquent
{

    protected $table = 'prices';

    public $timestamps = false;

}

<?php

namespace Energy;

interface IPrices
{

    public function getStandingElectricity();
    public function getStandingGas();

    public function getElectricityKwh();
    public function getGasKwh();

}

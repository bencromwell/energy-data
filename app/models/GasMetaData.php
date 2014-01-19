<?php

/**
 * @TODO pull from DB instead
 */
class GasMetaData
{

    public function getImperialConversionFactor()
    {
        return 2.83 / 3.6;
    }

    public function getCalorificValue()
    {
        return 39.5;
    }

    public function getCorrectionFactor()
    {
        return 1.02264;
    }

}

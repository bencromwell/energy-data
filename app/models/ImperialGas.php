<?php

/**
 * @property integer $id
 * @property string $date
 * @property integer $volume
 * @property integer $kwh
 */
class ImperialGas extends Gas
{

    public static function createNew($date, $volume, GasMetaData $meta)
    {
        $obj = new self();

        $obj->date = $date;

        $obj->volume = $volume;

        $obj->kwh = $obj->volume * $meta->getCalorificValue() * $meta->getCorrectionFactor() * $meta->getImperialConversionFactor();

        return $obj;
    }

}

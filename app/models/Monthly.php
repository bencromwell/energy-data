<?php

/**
 * @property string  $month
 * @property integer $type
 * @property integer $kwh
 */
class Monthly extends Eloquent
{

    protected $table = 'monthly';

    public $timestamps = false;

    public function getType()
    {
        return $this->type === \Energy\Etl\IDataStore::TYPE_ELECTRICITY ? 1 : 2;
    }

}

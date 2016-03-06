<?php

/**
 * @property integer $type
 * @property string $day
 * @property integer $kwh
 */
class Daily extends Eloquent
{

    protected $table = 'daily';

    public $timestamps = false;

    public function getKwh()
    {
        return $this->kwh;
    }

    public function getDate()
    {
        return $this->day;
    }

    public function getType()
    {
        return $this->type === \Energy\Etl\IDataStore::TYPE_ELECTRICITY ? 1 : 2;
    }

}

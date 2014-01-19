<?php

/**
 * @property integer $id
 * @property string $date
 * @property integer $volume
 * @property integer $kwh
 */
class Gas extends Eloquent implements Energy\ICalculable
{

    protected $table = 'f_gas';

    public $timestamps = false;

    public function getKwh()
    {
        return $this->kwh;
    }

    public function getDate()
    {
        return $this->date;
    }

}

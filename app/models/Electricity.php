<?php

/**
 * @property integer $id
 * @property string $date
 * @property integer $kwh
 */
class Electricity extends Eloquent implements \Energy\ICalculable
{

    protected $table = 'f_electricity';

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

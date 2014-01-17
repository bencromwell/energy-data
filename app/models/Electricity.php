<?php

/**
 * @property integer $id
 * @property string $date
 * @property integer $kwh
 */
class Electricity extends Eloquent
{

    protected $table = 'f_electricity';

    public $timestamps = false;

}

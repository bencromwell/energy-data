<?php

/**
 * @property integer $id
 * @property string $date
 * @property integer $volume
 * @property integer $kwh
 */
class Gas extends Eloquent
{

    protected $table = 'f_gas';

    public $timestamps = false;

}

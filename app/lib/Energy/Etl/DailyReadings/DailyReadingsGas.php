<?php

namespace Energy\Etl\DailyReadings;

use Energy\Etl\IDataStore;

class DailyReadingsGas extends DailyReadingsBase
{
    protected $type = IDataStore::TYPE_GAS;

    protected function getValues()
    {
        return \Gas::all()->sortBy(function (\Gas $model) {
            return $model->date;
        });
    }
}

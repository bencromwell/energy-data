<?php

namespace Energy\Etl\DailyReadings;

use Energy\Etl\IDataStore;

class DailyReadingsElectricity extends DailyReadingsBase
{
    protected $type = IDataStore::TYPE_ELECTRICITY;

    protected function getValues()
    {
        return \Electricity::all()->sortBy(function (\Electricity $model) {
            return $model->date;
        });
    }
}

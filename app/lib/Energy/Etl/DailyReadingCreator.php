<?php

namespace Energy\Etl;

use Carbon\Carbon;
use DateTime;

class DailyReadingCreator
{

    protected $type;
    protected $table;

    public function __construct($type)
    {
        $this->type = (int) $type;

        $this->table = $type === IDataStore::TYPE_ELECTRICITY ? 'f_electricity' : 'f_gas';
    }

    public function go()
    {
        if ($this->type === IDataStore::TYPE_ELECTRICITY) {
            $values = \Electricity::all()->sortBy(function (\Electricity $model) {
                return $model->date;
            });
        } else {
            $values = \Gas::all()->sortBy(function (\Gas $model) {
                return $model->date;
            });
        }

        // for now, just wipe it and recreate the lot
        // future addition - record the last reading we had and only process new values
        \DB::delete('DELETE FROM `daily` WHERE `type` = :type', [':type' => $this->type]);

        /** @var \Energy\ICalculable[] $values */

        /** @var \Daily[] $dailyReadings */
        $dailyReadings = [];

        /** @var \Energy\ICalculable $previous */
        $previous = null;
        foreach ($values as $model) {
            if (is_null($previous)) {
                $previous = $model;
                continue;
            }

            $thisDate = \Carbon\Carbon::createFromFormat('Y-m-d', $model->getDate());
            $previousDate = \Carbon\Carbon::createFromFormat('Y-m-d', $previous->getDate());

            $thisDate->setTime(0, 0, 0);
            $previousDate->setTime(0, 0, 0);

            $daysPassed = $thisDate->diffInDays($previousDate);
            $readingDiff = $model->getKwh() - $previous->getKwh();
            $kwhPerDay = round($readingDiff / $daysPassed, 2);

            $day = $previousDate;

            while ($thisDate->diffInDays($day) > 0) {
                $daily = new \Daily();
                $daily->type = $this->type;
                $daily->day = $day->format('Y-m-d');
                $daily->kwh = $kwhPerDay;
                $dailyReadings[] = $daily;
                //echo $day->format('Y-m-d') . ' = ' . $kwhPerDay . PHP_EOL;
                $day->addDay();
//                print_r($daily->attributesToArray());
            }

            //echo 'From ' . $previous->getDate() . ' to ' . $model->getDate() . ': ' . $kwhPerDay . PHP_EOL;

            $previous = $model;
        }

        \DB::transaction(function () use ($dailyReadings) {
            foreach ($dailyReadings as $value) {
                $value->save();
            }
        });

        // resulting query
        // SELECT SUM(kwh), MONTH(day) AS mth, YEAR(day) AS yr FROM `daily` WHERE `type` = 1 GROUP BY yr,mth ORDER BY yr,mth
    }

}

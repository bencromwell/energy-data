<?php

namespace Energy\Etl;

use Carbon\Carbon;

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

            $thisDate = Carbon::createFromFormat('Y-m-d', $model->getDate());
            $previousDate = Carbon::createFromFormat('Y-m-d', $previous->getDate());

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

                $day->addDay();
            }

            $previous = $model;
        }

        \DB::transaction(function () use ($dailyReadings) {
            foreach ($dailyReadings as $value) {
                $value->save();
            }
        });
    }

}

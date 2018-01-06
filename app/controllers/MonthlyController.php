<?php

class MonthlyController extends BaseController
{

    public function index()
    {
        $elec = $this->getReadings(\Energy\Etl\IDataStore::TYPE_ELECTRICITY);
        $gas = $this->getReadings(\Energy\Etl\IDataStore::TYPE_GAS);

        $prices = Price::all();

        $prices->sortBy(function (Price $price) {
            return $price->from;
        });

        /**
         * @param \Carbon\Carbon $testDate
         *
         * @return null|Price
         */
        $getPriceForDate = function (\Carbon\Carbon $testDate) use ($prices) {
            /** @var Price $price */
            foreach ($prices as $price) {
                // most recent price doesn't have a to date
                if ($price->to->getTimestamp() < 0) {
                    if ($testDate > $price->from) {
                        return $price;
                    }
                }

                if ($testDate->between($price->from, $price->to)) {
                    return $price;
                }
            }

            return null; // no price for the date
        };

        $elecCalc = function ($kwh, $date) use ($prices, $getPriceForDate) {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $date);
            /** @var Price $price */
            $price = $getPriceForDate($date);
            if ($price) {
                return (($price->getStandingElectricity()) + ($price->getElectricityKwh() * $kwh)) / 100;
            }
            return '';
        };

        $gasCalc = function ($kwh, $date) use ($prices, $getPriceForDate) {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $date);
            /** @var Price $price */
            $price = $getPriceForDate($date);
            if ($price) {
                return (($price->getStandingGas()) + ($price->getGasKwh() * $kwh)) / 100;
            }
            return '';
        };

        $eData = [];
        $gData = [];

        $startFrom = '2013-09-01';
        $chart = ['start' => strtotime($startFrom) * 1000];

        $chart['e'] = [];
        $chart['g'] = [];

        $standardise = \Input::get('standardise', false);

        $this->populateData($elec, $chart['e'], $eData, $standardise);
        $this->populateData($gas, $chart['g'], $gData, $standardise);

        $years = array_unique(array_merge(array_keys($eData), array_keys($gData)));

        return View::make('monthly', array(
            'years'       => $years,
            'electricity' => $eData,
            'gas'         => $gData,
            'gCalc'       => $gasCalc,
            'eCalc'       => $elecCalc,

            'chart'       => json_encode($chart),
        ));
    }

    private function getReadings($type)
    {
        $sql = <<<SQL
SELECT
    SUM(`kwh`) AS reading,
    MONTH(`day`) AS mth,
    YEAR(`day`) AS yr
FROM
    `daily`
WHERE
    `type` = :type
AND
    `day` < :pointInTime
GROUP BY yr, mth
ORDER BY yr, mth
SQL;

        $pointInTime = new \Carbon\Carbon();
        $pointInTime->startOfMonth();

        return \DB::select($sql, [
            ':type' => $type,
            ':pointInTime' => $pointInTime->format('Y-m-d'),
        ]);
    }

    private function populateData($sourceData, &$chart, &$dataRows, $standardise = false)
    {
        foreach ($sourceData as $model) {
            $monthlyEntity = $this->processReading($model);

            $reading = $standardise ? $monthlyEntity->getStandardisedReading() : $monthlyEntity->getReading();

            array_push($chart, [
                    $monthlyEntity->getJsTimestamp(),
                    $reading
                ]
            );

            $obj = new stdClass();
            $obj->month = $monthlyEntity->getMonth();
            $obj->kwh = $reading;

            if (empty($dataRows[$monthlyEntity->getYear()])) {
                $dataRows[$monthlyEntity->getYear()] = [];
            }

            $dataRows[$monthlyEntity->getYear()][$monthlyEntity->getDateTime()->month] = $obj;
        }
    }

    private function processReading($model)
    {
        $dateTime = \Carbon\Carbon::createFromFormat('Y-m', $model->yr . '-' . $model->mth);
        $dateTime->setTime(0, 0, 0);

        return new \Energy\MonthlyReadingEntity($model->reading, $dateTime);
    }

}

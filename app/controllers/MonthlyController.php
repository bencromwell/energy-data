<?php

class MonthlyController extends BaseController
{

    public function index()
    {
        $elec = \DB::select('SELECT SUM(kwh) AS reading, MONTH(day) AS mth, YEAR(day) AS yr FROM `daily` WHERE `type` = 1 GROUP BY yr,mth ORDER BY yr,mth');
        $gas = \DB::select('SELECT SUM(kwh) AS reading, MONTH(day) AS mth, YEAR(day) AS yr FROM `daily` WHERE `type` = 2 GROUP BY yr,mth ORDER BY yr,mth');

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
            return (($price->getStandingElectricity()) + ($price->getElectricityKwh() * $kwh)) / 100;
        };

        $gasCalc = function ($kwh, $date) use ($prices, $getPriceForDate) {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $date);
            /** @var Price $price */
            $price = $getPriceForDate($date);
            return (($price->getStandingGas()) + ($price->getGasKwh() * $kwh)) / 100;
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

        $from = DateTime::createFromFormat('Y-m-d', $startFrom); // todo: config or retrieve dynamically
        $from->setTime(0, 0, 0);

        $now  = new DateTime();
        $now->setDate(date('Y'), date('m'), 1);
        $now->setTime(0, 0, 1);

        $months = array();
        do {
            $months[] = $from->format('Y-m');

            // don't like this duplicate condition. too tired to think of an alternative right now!
            if ($from->getTimestamp() < $now->getTimestamp()) {
                $from->add(new DateInterval('P1M'));
            }

        } while ($from->getTimestamp() < $now->getTimestamp());

        return View::make('monthly', array(
            'months'      => $months,
            'electricity' => $eData,
            'gas'         => $gData,
            'gCalc'       => $gasCalc,
            'eCalc'       => $elecCalc,

            'chart'       => json_encode($chart),
        ));
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

            $dataRows[] = $obj;
        }
    }

    private function processReading($model)
    {
        $dateTime = \Carbon\Carbon::createFromFormat('Y-m', $model->yr . '-' . $model->mth);
        $dateTime->setTime(0, 0, 0);

        return new \Energy\MonthlyReadingEntity($model->reading, $dateTime);
    }

}

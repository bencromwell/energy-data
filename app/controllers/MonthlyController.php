<?php

class MonthlyController extends BaseController
{

    public function index()
    {
        $elec = \DB::select('SELECT SUM(kwh) AS reading, MONTH(day) AS mth, YEAR(day) AS yr FROM `daily` WHERE `type` = 1 GROUP BY yr,mth ORDER BY yr,mth');
        $gas = \DB::select('SELECT SUM(kwh) AS reading, MONTH(day) AS mth, YEAR(day) AS yr FROM `daily` WHERE `type` = 2 GROUP BY yr,mth ORDER BY yr,mth');


        // @todo standardise results to a 30 day month

        // for now just go off most recent price
        $prices = Price::all()->last();
        $elecCalc = function ($kwh) use ($prices) {
            return (($prices->getStandingElectricity()) + ($prices->getElectricityKwh() * $kwh)) / 100;
        };

        $gasCalc = function ($kwh) use ($prices) {
            return (($prices->getStandingGas()) + ($prices->getGasKwh() * $kwh)) / 100;
        };

        $eData = [];
        $gData = [];

        $startFrom = '2013-09-01';
        $chart = array('start' => strtotime($startFrom) * 1000);

        $chart['e'] = array();
        foreach ($elec as $model) {
            $dateTime = \Carbon\Carbon::createFromFormat('Y-m', $model->yr . '-' . $model->mth);
            $dateTime->setTime(0, 0, 0);

            array_push($chart['e'], array(
                ($dateTime->getTimestamp() * 1000), (int) round($model->reading))
            );

            $obj = new stdClass();
            $obj->month = $dateTime->format('Y-m');
            $obj->kwh = (int) round($model->reading);

            $eData[] = $obj;
        }

        $chart['g'] = array();
        foreach ($gas as $model) {
            $dateTime = \Carbon\Carbon::createFromFormat('Y-m', $model->yr . '-' . $model->mth);
            $dateTime->setTime(0, 0, 0);

            array_push($chart['g'], array(
                ($dateTime->getTimestamp() * 1000), (int) round($model->reading))
            );

            $obj = new stdClass();
            $obj->month = $dateTime->format('Y-m');
            $obj->kwh = (int) round($model->reading);

            $gData[] = $obj;
        }

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

}

<?php

class MonthlyController extends BaseController
{

    public function index()
    {
        $from = DateTime::createFromFormat('Y-m-d', '2013-12-01');
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


        $where = '`type` = ' . \Energy\Etl\IDataStore::TYPE_ELECTRICITY . ' AND (';
        foreach ($months as $month) {
            $where .= "`month` = '" . $month . "' OR ";
        }
        $where = substr($where, 0, -3);
        $where .= ')';
        $e = Monthly::whereRaw($where);

        $where = '`type` = ' . \Energy\Etl\IDataStore::TYPE_GAS . ' AND (';
        foreach ($months as $month) {
            $where .= "`month` = '" . $month . "' OR ";
        }
        $where = substr($where, 0, -3);
        $where .= ')';
        $g = Monthly::whereRaw($where);

        $prices = Price::all()->first();
        $elecCalc = function ($kwh) use ($prices) {
            return (($prices->getStandingElectricity() * 30) + ($prices->getElectricityKwh() * $kwh * 30)) / 100;
        };

        $gasCalc = function ($kwh) use ($prices) {
            return (($prices->getStandingGas() * 30) + ($prices->getGasKwh() * $kwh * 30)) / 100;
        };

        return View::make('monthly', array(
            'months'      => $months,
            'electricity' => $e->get(),
            'gas'         => $g->get(),
            'gCalc'       => $gasCalc,
            'eCalc'       => $elecCalc,
        ));
    }

}

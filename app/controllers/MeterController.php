<?php

use Energy\CostCalculator;

class MeterController extends BaseController
{

    private function getPricesModel()
    {
        $prices = Price::all();
        return $prices[0];
    }

    public function postMeterReadings()
    {
        $post = Input::all();

        $date = $post['date'];
        if (!empty($date)) {
            $dt = DateTime::createFromFormat('Y-m-d', $date);
            if (!$dt) {
                throw new InvalidArgumentException('Invalid Date');
            }
        }

        $post['electricity'] = (int) $post['electricity'];
        $post['gas']         = (int) $post['gas'];

        if (!empty($post['electricity']) && $post['electricity'] > 0) {

            $e = new Electricity();
            $e->date = $date;
            $e->kwh = $post['electricity'];
            $e->save();

        }

        if (!empty($post['gas']) && $post['gas'] > 0) {

            $g = ImperialGas::createNew($date, $post['gas'], new GasMetaData());
            $g->save();

        }

        return Redirect::to('/last-reading');
    }

    public function getMeterReadings()
    {
        $calc = new CostCalculator(CostCalculator::TYPE_ELECTRICITY);

        $prices = $this->getPricesModel();

        // Elec

        // Get the last two readings, calculate the diff in kwh
        $models = Electricity::orderBy('id', ' DESC')->limit(2)->get();
        if (count($models) === 2) {
            $eRes = $calc->calculate($models[0], $models[1], $prices);
        } else {
            $eRes = false;
        }

        // Gas

        $calc = new CostCalculator(CostCalculator::TYPE_GAS);
        $models = Gas::orderBy('id', ' DESC')->limit(2)->get();

        if (count($models) === 2) {
            $gRes = $calc->calculate($models[0], $models[1], $prices);
        } else {
            $gRes = false;
        }

        return View::make('last-reading')->nest('child', 'meter-data', array(
            'eRes' => $eRes,
            'gRes' => $gRes,
        ));
    }

    public function getOverall()
    {
        $prices = $this->getPricesModel();

        $eCalc = new CostCalculator(CostCalculator::TYPE_ELECTRICITY);
        $gCalc = new CostCalculator(CostCalculator::TYPE_GAS);

        $first = Electricity::orderBy('id', 'DESC')->limit(1)->get();
        $last  = Electricity::orderBy('id', 'ASC')->limit(1)->get();

        if (!empty($first[0]) && !empty($last[0]) && $first[0]->id !== $last[0]->id) {
            $eRes = $eCalc->calculate($first[0], $last[0], $prices);
        } else {
            $eRes = false;
        }

        $first = Gas::orderBy('id', 'DESC')->limit(1)->get();
        $last  = Gas::orderBy('id', 'ASC')->limit(1)->get();

        if (!empty($first[0]) && !empty($last[0]) && $first[0]->id !== $last[0]->id) {
            $gRes = $gCalc->calculate($first[0], $last[0], $prices);
        } else {
            $gRes = false;
        }

        return View::make('overall')->nest('child', 'meter-data', array(
            'eRes' => $eRes,
            'gRes' => $gRes,
        ));
    }

}

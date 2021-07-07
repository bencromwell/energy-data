<?php

use Energy\SmartMeters;

class MeterController extends BaseController
{

    private function getPricesModel()
    {
        return Price::all()->last();
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

        // horrendous horrendous hack alert - smart meters were installed resetting the readings to 0
        if (SmartMeters::isSmartMeter($dt)) {
            $post['electricity'] = SmartMeters::addElectric($post['electricity']);
            $post['gas'] = SmartMeters::addGas($post['gas']);
        }

        if (!empty($post['electricity']) && $post['electricity'] > 0) {

            $e = new Electricity();
            $e->date = $date;
            $e->kwh = $post['electricity'];
            $e->save();

            $calculator = new \Energy\Etl\DailyReadings\DailyReadingsElectricity();
        }

        if (!empty($post['gas']) && $post['gas'] > 0) {

            $g = ImperialGas::createNew($date, $post['gas'], new GasMetaData());
            $g->save();

            $calculator = new \Energy\Etl\DailyReadings\DailyReadingsGas();
        }

        if (isset($calculator)) {
            $calculator->go();
        }

        return Redirect::to('/last-reading');
    }

    /**
     * @param Gas|null         $gasModel1
     * @param Gas|null         $gasModel2
     * @param Electricity|null $elecModel1
     * @param Electricity|null $elecModel2
     * @param string           $viewName
     *
     * @return \Illuminate\View\View
     */
    private function readingsComparisonView($gasModel1, $gasModel2, $elecModel1, $elecModel2, $viewName)
    {
        $prices = $this->getPricesModel();

        $eCalc = new \Energy\ElectricityCostCalculator();
        $gCalc = new \Energy\GasCostCalculator();

        if (!is_null($elecModel1) && !is_null($elecModel2) && $elecModel1->id !== $elecModel2->id) {
            $eRes = $eCalc->calculate($elecModel1, $elecModel2, $prices);
        } else {
            $eRes = false;
        }

        if (!is_null($gasModel1) && !is_null($gasModel2) && $gasModel1->id !== $gasModel2->id) {
            $gRes = $gCalc->calculate($gasModel1, $gasModel2, $prices);
        } else {
            $gRes = false;
        }

        return View::make($viewName)->nest('child', 'meter-data', array(
            'eDate' => is_null($elecModel1) ? '' : date('d.m.Y', strtotime($elecModel1->date)),
            'gDate' => is_null($gasModel1) ? '' : date('d.m.Y', strtotime($gasModel1->date)),
            'eRes'  => $eRes,
            'gRes'  => $gRes,
            'last' => array(
                'g' => $gasModel1->volume,
                'e' => $elecModel1->getKwh(),
            ),
            'lastSmart' => array(
                'g' => SmartMeters::subtractGas($gasModel1->volume),
                'e' => SmartMeters::subtractElectric($elecModel1->getKwh()),
            ),
        ));
    }

    public function getMeterReadings()
    {
        /** @var $gModels \Illuminate\Database\Eloquent\Collection */
        $gModels = Gas::orderBy('date', ' DESC')->limit(2)->get();

        /** @var $eModels \Illuminate\Database\Eloquent\Collection */
        $eModels = Electricity::orderBy('date', ' DESC')->limit(2)->get();

        return $this->readingsComparisonView(
            $gModels->get(0), $gModels->get(1),
            $eModels->get(0), $eModels->get(1),
            'last-reading'
        );
    }

    public function getOverall()
    {
        /** @var $first Gas|null */
        $gFirst = Gas::orderBy('date', 'DESC')->limit(1)->get()->first();
        /** @var $last Gas|null */
        $gLast  = Gas::orderBy('date', 'ASC')->limit(1)->get()->first();

        /** @var $first Electricity */
        $eFirst = Electricity::orderBy('date', 'DESC')->limit(1)->get()->first();
        /** @var $last Electricity */
        $eLast  = Electricity::orderBy('date', 'ASC')->limit(1)->get()->first();

        return $this->readingsComparisonView(
            $gFirst, $gLast,
            $eFirst, $eLast,
            'overall'
        );
    }

}

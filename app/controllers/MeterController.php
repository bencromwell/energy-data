<?php

use Energy\CostCalculator;

class MeterController extends BaseController
{

    private function getPricesModel()
    {
        return Price::all()->first();
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

            $ma = new \Energy\Etl\MonthlyAggregator(
                new \Energy\Etl\MysqlStore(\Energy\Etl\IDataStore::TYPE_ELECTRICITY)
            );

            $ma->run(new DateTime());
        }

        if (!empty($post['gas']) && $post['gas'] > 0) {

            $g = ImperialGas::createNew($date, $post['gas'], new GasMetaData());
            $g->save();

            $ma = new \Energy\Etl\MonthlyAggregator(
                new \Energy\Etl\MysqlStore(\Energy\Etl\IDataStore::TYPE_GAS)
            );

            $ma->run(new DateTime());
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

        $eCalc = new CostCalculator(CostCalculator::TYPE_ELECTRICITY);
        $gCalc = new CostCalculator(CostCalculator::TYPE_GAS);

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

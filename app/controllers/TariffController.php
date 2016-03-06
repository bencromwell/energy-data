<?php

use Carbon\Carbon;

class TariffController extends BaseController
{

    public function index()
    {
        $prices = Price::all()->sortBy(function (Price $price) {
            return $price->from;
        }, SORT_REGULAR, true);

        $getDuration = function (Carbon $from, Carbon $to) {
            if ($to->getTimestamp() < 0) {
                $to = new Carbon();
            }

            return $to->diffInMonths($from);
        };

        return View::make('tariff', array(
            'prices' => $prices,
            'getDuration' => $getDuration,
        ));
    }

}

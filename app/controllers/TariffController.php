<?php

class TariffController extends BaseController
{

    public function index()
    {
        $prices = Price::all()->first();

        return View::make('tariff', array(
            'prices' => $prices,
        ));
    }

}

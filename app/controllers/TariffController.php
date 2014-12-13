<?php

class TariffController extends BaseController
{

    public function index()
    {
        $prices = Price::all()->last();

        return View::make('tariff', array(
            'prices' => $prices,
        ));
    }

}

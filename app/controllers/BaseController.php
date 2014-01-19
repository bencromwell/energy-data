<?php

class BaseController extends Controller
{

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }

        $e = Electricity::orderBy('id', 'DESC')->limit(1)->get()->first();
        $g = Gas::orderBy('id', 'DESC')->limit(1)->get()->first();

        View::share('lastGas', is_null($g) ? '' : $g->volume);
        View::share('lastElectricity', is_null($e) ? '' : $e->kwh);
    }

}

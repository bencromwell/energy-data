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

        $e = Electricity::orderBy('id', 'DESC')->limit(1)->get();
        $g = Gas::orderBy('id', 'DESC')->limit(1)->get();

        View::share('lastGas', $g[0]->volume);
        View::share('lastElectricity', $e[0]->kwh);
    }

}

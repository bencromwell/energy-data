@extends('layout')

@section('title')
| Monthly
@stop

@section('content')

<div class="row">
    <div class="large-12 columns">
        <h1>Monthly</h1>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">

        <p>
            OK, so this is where things should get a little bit more interesting. We expect, for a gas-heated property,
            that electricity readings will not fluctuate an awful lot over a year. However, gas readings will change a
            lot depending on how abnormally warm or cold a particular month is. What we need here is to calculate, as
            best we can, the monthly consumption. This is problematic when we can't guarantee the reliability of the
            raw data (i.e. that it will have a regular and consistent granularity). So we need to extrapolate the data
            for each month from what we have available. The more a user reads their meters, the more reliable the
            analysis. Eventually, it should be possible to predict next month's bill based on next month's localised
            temperature readings, although we'd have to start recording current temperatures too. For now, that
            constitutes feature creep. So, we'll make do with trying to get a nice monthly breakdown that we can
            subsequently use to see if we're doing better next year than last year.
        </p>

    </div>
</div>

@stop

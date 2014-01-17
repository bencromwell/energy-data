@extends('layout')

@section('title')
| Overall Data
@stop

@section('content')

<div class="row">
    <div class="large-12 columns">
        <h1>Overall Data</h1>
    </div>
</div>

{{ $child }}

<div class="row">
    <div class="large-12 columns">
        <div class="panel radius">
            Overall Data: <br>
            Takes the difference between the first and last readings in the system.<br>
            Compare this with the last reading to get an idea as to how you're doing.
        </div>
    </div>
</div>

@stop

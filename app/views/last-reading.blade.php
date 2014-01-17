@extends('layout')

@section('title')
| Last Reading
@stop

@section('content')

<div class="row">
    <div class="large-12 columns">
        <h1>Last Reading</h1>
    </div>
</div>

{{ $child }}

<div class="row">
    <div class="large-12 columns">
        <div class="panel radius">
            Last Reading: <br>
            Takes the difference between the last two given readings.
        </div>
    </div>
</div>

@stop

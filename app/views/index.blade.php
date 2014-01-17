@extends('layout')

@section('title')
| Submit Readings
@stop

@section('content')

<div class="row">
    <div class="large-12 columns">
        <h1>Submit Readings</h1>
    </div>
</div>

{{ Form::open(array('url' => 'meter-readings')) }}

<div class="row">
    <div class="large-12 columns">
        {{ Form::label('date', 'Date') }}
        {{ Form::input('date', 'date', date('Y-m-d')) }}
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        {{ Form::label('electricity', 'Electricity') }}
        {{ Form::input('number', 'electricity', '') }}
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        {{ Form::label('gas', 'Gas') }}
        {{ Form::input('number', 'gas', '') }}
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        {{ Form::submit('Submit', array('class' => 'button radius')) }}
    </div>
</div>

{{ Form::close() }}

@stop

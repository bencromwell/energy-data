@extends('layout')

@section('title')
| Tariff
@stop

@section('content')

<div class="row">
    <div class="large-12 columns">
        <h1>
            Tariff
            <small>in pence</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <table>
            <thead>
                <tr>
                    <th>Electricity kWh</th>
                    <th class="right-border">Electricity Standing</th>
                    <th>Gas kWh</th>
                    <th class="right-border">Gas Standing</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
            @foreach($prices as $price)
            <tr>
                <td>{{ $price->electricity_kwh }}</td>
                <td class="right-border">{{ $price->electricity_standing }}</td>
                <td>{{ $price->gas_kwh }}</td>
                <td class="right-border">{{ $price->gas_standing }}</td>
                <td>{{ $price->from->format('Y-m-d') }}</td>
                <td>{{ $price->to->getTimestamp() > 0 ? $price->to->format('Y-m-d') : '-' }}</td>
                <td>{{ $getDuration($price->from, $price->to) }} months</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

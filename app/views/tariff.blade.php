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
    <div class="large-6 columns">
        <table>
            <thead>
                <tr>
                    <th>Electricity kWh</th>
                    <th>Electricity Standing</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $prices->electricity_kwh }}</td>
                <td>{{ $prices->electricity_standing }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="large-6 columns">
        <table>
            <thead>
                <tr>
                    <th>Gas kWh</th>
                    <th>Gas Standing</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $prices->gas_kwh }}</td>
                <td>{{ $prices->gas_standing }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

@stop

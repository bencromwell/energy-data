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

<hr>

<div class="row">
    <div class="large-12 columns">

        <div id="chart"></div>

    </div>
</div>

<hr>

<div class="row">
    <div class="large-6 columns">
        <h2>Electricity</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>kWh</th>
                    <th>Cost</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($electricity as $e)
                <tr>
                    <td>{{ $e->month }}</td>
                    <td>{{ $e->kwh * 30 }}</td>
                    <td>£{{ number_format($eCalc($e->kwh),2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="large-6 columns">
        <h2>Gas</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>kWh</th>
                    <th>Cost</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($gas as $g)
                <tr>
                    <td>{{ $g->month }}</td>
                    <td>{{ $g->kwh * 30 }}</td>
                    <td>£{{ number_format($gCalc($g->kwh),2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

<script>
    window.chartData = {{ $chart }};
</script>

@stop

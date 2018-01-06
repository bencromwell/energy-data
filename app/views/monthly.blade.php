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

        <div id="chart"></div>

    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <ul class="button-group">
            <li>
                <a class="button {{ Input::get('standardise', false) ? 'blue' : 'secondary' }} tiny active" href="{{ URL::route('monthly', ['standardise' => 1]) }}">
                    Standardised Readings (30 day months)
                </a>
            </li>
            <li>
                <a class="button {{ Input::get('standardise', false) ? 'secondary' : 'blue' }} tiny" href="{{ URL::route('monthly', ['standardise' => 0]) }}">
                    Raw Readings
                </a>
            </li>
        </ul>
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
                    @foreach ($years as $year)
                        <th>{{ $year }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @for($i = 1; $i<= 12; $i++)
                @if(isset($electricty[$i]))
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $electricity[$i]->kwh }}</td>
                        <td>£{{ number_format($eCalc($electricity[$i]->kwh, $electricity[$i]->month) ,2) }}</td>
                    </tr>
                @endif
            @endfor
            </tbody>
        </table>
    </div>
    <div class="large-6 columns">
        <h2>Gas</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    @foreach ($years as $year)
                        <th>{{ $year }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @for($i = 1; $i<= 12; $i++)
                @if(isset($gas[$i]))
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $gas[$i]->kwh }}</td>
                        <td>£{{ number_format($gCalc($gas[$i]->kwh, $gas[$i]->month) ,2) }}</td>
                    </tr>
                @endif
            @endfor
            </tbody>
        </table>

    </div>
</div>

<script>
    window.chartData = {{ $chart }};
</script>

@stop

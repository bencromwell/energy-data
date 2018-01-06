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
            @for($i = 1; $i <= 12; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    @foreach ($years as $year)
                        @if(isset($electricity[$year], $electricity[$year][$i]))
                        <td>
                            {{ $electricity[$year][$i]->kwh }}<br>
                            £{{ number_format($eCalc($electricity[$year][$i]->kwh, $electricity[$year][$i]->month) ,2) }}
                        </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
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
            @for($i = 1; $i <= 12; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    @foreach ($years as $year)
                        @if(isset($gas[$year], $gas[$year][$i]))
                        <td>
                            {{ $gas[$year][$i]->kwh }}<br>
                            £{{ number_format($gCalc($gas[$year][$i]->kwh, $gas[$year][$i]->month) ,2) }}
                        </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endfor
            </tbody>
        </table>

    </div>
</div>

<script>
    window.chartData = {{ $chart }};
</script>

@stop

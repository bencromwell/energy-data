<div class="row">
    <div class="large-6 columns">
        <h2>
            Electricity
            <small>{{ $eDate }}</small>
        </h2>
        @if ($eRes)
        <div class="alert-box small secondary">
            {{ $last['e'] }} <small>({{ $lastSmart['e'] }})</small>
        </div>
        <table>
            <tr>
                <th>Usage (kWh)</th>
                <th>{{ $eRes->getKwh() }}</th>
            </tr>
            <tr>
                <th>Cost</th>
                <th>£{{ number_format($eRes->getCost(), 2) }}</th>
            </tr>
            <tr>
                <th>Days</th>
                <th>{{ $eRes->getDays() }}</th>
            </tr>
            <tr>
                <th>Cost / Day</th>
                <th>£{{ number_format($eRes->getDailyCost(), 2) }}</th>
            </tr>
            <tr>
                <th>Weekly</th>
                <th>£{{ number_format($eRes->getWeeklyCost(), 2) }}</th>
            </tr>
            <tr>
                <th>Monthly (30 days)</th>
                <th>£{{ number_format($eRes->getMonthlyCost(), 2) }}</th>
            </tr>
            <tr>
                <th>Monthly Usage (kWh)</th>
                <th>{{ $eRes->getMonthlyUsage() }}</th>
            </tr>
        </table>
        @else
        <div class="panel callout radius">
            Not enough data.
        </div>
        @endif
    </div>
    <div class="large-6 columns">
        <h2>
            Gas
            <small>{{ $gDate }}</small>
        </h2>
        <div class="alert-box small secondary">
            {{ $last['g'] }} <small>({{ $lastSmart['g'] }})</small>
        </div>
        @if ($gRes)
        <table>
            <tr>
                <th>Usage (kWh)</th>
                <th>{{ $gRes->getKwh() }}</th>
            </tr>
            <tr>
                <th>Cost</th>
                <th>£{{ number_format($gRes->getCost(), 2) }}</th>
            </tr>
            <tr>
                <th>Days</th>
                <th>{{ $gRes->getDays() }}</th>
            </tr>
            <tr>
                <th>Cost / Day</th>
                <th>£{{ number_format($gRes->getDailyCost(), 2) }}</th>
            </tr>
            <tr>
                <th>Weekly</th>
                <th>£{{ number_format($gRes->getWeeklyCost(), 2) }}</th>
            </tr>
            <tr>
                <th>Monthly (30 days)</th>
                <th>£{{ number_format($gRes->getMonthlyCost(), 2) }}</th>
            </tr>
            <tr>
                <th>Monthly Usage (kWh)</th>
                <th>{{ $gRes->getMonthlyUsage() }}</th>
            </tr>
        </table>
        @else
        <div class="panel callout radius">
            Not enough data.
        </div>
        @endif
    </div>
</div>

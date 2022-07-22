<!DOCTYPE html>
<html lang="{{ \Lang::getLocale() }}">
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
        <tr>
            <th class="th-auto">@lang('main.reports.columns.id')</th>
            <th class="th-auto">@lang('main.reports.columns.created')</th>
            @if (!isset($filters['client']))
                <th class="th-auto">@lang('main.reports.columns.client')</th>
            @endif
            <th class="th-auto">@lang('main.reports.columns.area')</th>
            <th class="th-auto">@lang('main.reports.columns.module')</th>
            <th class="th-auto">@lang('main.reports.columns.problem')</th>
            <th class="th-auto">@lang('main.reports.columns.status')</th>
            <th class="th-auto">@lang('main.reports.columns.assigned')</th>
            <th class="th-auto">@lang('main.reports.columns.title')</th>
            <th class="th-auto">@lang('main.reports.columns.resolved')</th>
            <th class="th-auto">@lang('main.reports.columns.closed')</th>
            <th class="th-auto">@lang('main.reports.columns.sla')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($incidents as $inc)
        <tr>
            <td>{{sprintf("%'.06d", $inc['id'])}}</td>
            <td>{{$inc['open']}}</td>
            @if (!isset($filters['client']))
                <td>{{$inc['client_desc']}}</td>
            @endif
            <td>{{$inc['area_desc']}}</td>
            <td>{{$inc['module_desc']}}</td>
            <td>{{$inc['problem_desc']}}</td>
            <td>{{ __('main.status.'.$inc['status_desc']) }}</td>
            <td>{{$inc['assigned_desc']}}</td>
            <td>{{$inc['title']}}</td>
            <td>{{$inc['resolution'] ?? ''}}</td>
            <td>{{$inc['close'] ?? ''}}</td>
            <td>{{$inc['sla_desc'] ?? ''}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
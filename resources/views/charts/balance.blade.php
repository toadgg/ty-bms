@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>项目报表</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header no-padding">
                <div class="mailbox-read-info">
                    <h3>{{ $calendar['current']->format('Y年m月') }}应收工程款计划</h3>
                </div>
                <div class="box-tools pull-right">
                    <a href="{{ route('charts.balance.index', [ 'c' => $calendar['prev']->format('Y-m')]) }}" class="btn btn-box-tool " data-toggle="tooltip" title="" data-original-title="{{ $calendar['prev']->format('Y年m月') }}"><i class="fa fa-chevron-left"></i>{{ $calendar['prev']->format('Y年m月') }}</a>
                    <a href="{{ route('charts.balance.index', [ 'c' => $calendar['next']->format('Y-m')]) }}" class="btn btn-box-tool @if($calendar['current']->format('Y-m') == date('Y-m', time())) disabled @endif" data-toggle="tooltip" title="" data-original-title="{{ $calendar['next']->format('Y年m月') }}"><i class="fa">{{ $calendar['next']->format('Y年m月') }}</i><i class="fa fa-chevron-right"></i></a>
                </div>
            </div>

            <div class="box-body nprogress">
                <div id="tableShadow" class="table-warp">
                    @include('components.loading')
                    <div style="display:none;" >
                        <table class="table table-bordered table-striped dataTable nowrap @if(false) responsive @endif" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>项目名称</th>
                                <th>合同金额</th>
                                <th>预付款</th>
                                <th>付款比例</th>
                                <th>付款方式</th>
                                <th>预付款扣除规则</th>
                                <th>建筑面积(㎡)</th>
                                <th>已完总产值</th>
                                <th>{{ $calendar['prev']->month }}月完成产值</th>
                                <th>本月应收金额</th>
                                <th>累计应收总金额</th>
                                <th>已扣预付款</th>
                                <th>已收工程款</th>
                                <th>欠款总金额</th>
                                <th>实际可收取工程款</th>
                                <th>计划支出</th>
                                <th>{{ $calendar['prev']->month }}月已收取工程款</th>
                                <th>主管领导</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($balances as $index=>$balance)
                                <tr class="@if(false) danger text-danger @endif">
                                    <td>{{ $balance['id'] }}</td>
                                    <td><span class="@if($balance['available_capital'] < $balance['planned_expenditure']) danger text-danger fa fa-warning @endif"></span>{{ $balance['name'] }}</td>
                                    <td class="currency">{{ format_currency($balance['signed_money']) }}</td>
                                    <td class="currency">{{ format_currency($balance['advance_payment_amount']) }}</td>
                                    <td class="percent">{{ $balance['progress_payment_pct'] }} %</td>
                                    <td>{{ $balance['pay_mode'] }}</td>
                                    <td>{{ $balance['advance_payment_mode'] }}</td>
                                    <td class="decimal">{{ format_decimal($balance['build_area'], '㎡') }} </td>
                                    <td class="currency">{{ format_currency($balance['total_output_value']) }}</td>
                                    <td class="currency">{{ format_currency($balance['last_month_output_value']) }}</td>
                                    <td class="currency"><span class="@if($balance['warnings']['current_month_receivable'] && $balance['current_month_receivable']) danger text-warning fa fa-warning @endif"></span>{{ format_currency($balance['current_month_receivable']) }}</td>
                                    <td class="currency"><span class="@if($balance['warnings']['total_receivable'] && $balance['total_receivable']) danger text-warning fa fa-warning @endif"></span>{{ format_currency($balance['total_receivable']) }}</td>
                                    <td class="currency"><span class="@if($balance['warnings']['advance_payment_deducted'] && $balance['advance_payment_deducted']) danger text-warning fa fa-warning @endif"></span>{{ format_currency($balance['advance_payment_deducted']) }}</td>
                                    <td class="currency">{{ format_currency($balance['total_receipt']) }}</td>
                                    <td class="currency">{{ format_currency($balance['total_arrears']) }}</td>
                                    <td class="currency"><span class="@if($balance['warnings']['current_month_receivable'] && $balance['available_capital']) danger text-warning fa fa-warning @endif"></span>{{ format_currency($balance['available_capital']) }}</td>
                                    <td class="currency">{{ format_currency($balance['planned_expenditure']) }}</td>
                                    <td class="currency">{{ format_currency($balance['last_month_receipt']) }}</td>
                                    <td>{{ $balance['manager'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
@endpush

@push('js')

    <!-- InputMask -->
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.numeric.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/jquery.inputmask.min.js') }}"></script>

    <script>
        var opts = {
            //responsive: true,
            //columnDefs : []
        };
        $('#tyTable').DataTable(opts);
    </script>
@endpush
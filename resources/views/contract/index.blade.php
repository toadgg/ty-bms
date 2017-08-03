@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>档案管理</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">合同档案管理</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body nprogress">
                <div id="tableShadow" class="table-warp">
                    @include('components.loading')
                    <div style="display:none;" >
                        <table id="table" class="table table-bordered table-striped dataTable nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>操作</th>
                                <th>合同编码</th>
                                <th>合同名称</th>
                                <th>合同类型</th>
                                <th data-class="text-right">签订金额</th>
                                <th>付款方式</th>
                                <th data-class="text-right">预付金额</th>
                                <th>预付扣除方式</th>
                                <th data-class="text-right">进度款比例</th>
                                <th>签订日期</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($contracts as $index=>$contract)
                                <tr>
                                    <td>{{ $contract->id }}</td>
                                    <th><a class="fa fa-cogs" href="{{ route('contracts.edit', $contract->id) }}"></a></th>
                                    <td>{{ $contract->code }}</td>
                                    <td>{{ $contract->name }}</td>
                                    <td>{{ $contract->type }}</td>
                                    <td>{{ format_currency($contract->signed_money) }}</td>

                                    <td>{{ $contract->pay_mode }}</td>
                                    <td>{{ format_currency($contract->advance_payment_amount) }}</td>
                                    <td>{{ $contract->advance_payment_mode }}</td>
                                    <td>{{ $contract->progress_payment_pct }}%</td>

                                    <td>{{ $contract->signed_date }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
@stop

@push('js')
<script type="text/javascript">
    $('#tyTable').DataTable({order: []});
</script>
@endpush
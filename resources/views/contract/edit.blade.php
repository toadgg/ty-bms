@extends('adminlte.page')

@section('title', '天一建设')

@section('content_header')
    <h1>合同档案设置</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header no-padding">
                <div class="mailbox-read-info">
                    <h3>{{ $currentItem->name }}</h3>
                    <h5>合同编码: {{ $currentItem->code }}<span class="mailbox-read-time pull-right">合同签订时间: {{ $currentItem->signed_date }}</span></h5>
                </div>
                <div class="box-tools pull-right">
                    <a href="{{ route('contracts.edit', $hasPerItem ? $perItem->id : $currentItem->id) }}" class="btn btn-box-tool @if(!$hasPerItem) disabled @endif" data-toggle="tooltip" title="" data-original-title="上一页"><i class="fa fa-chevron-left"></i></a>
                    <a href="{{ route('contracts.edit', $hasNextItem ? $nextItem->id : $currentItem->id) }}" class="btn btn-box-tool @if(!$hasNextItem) disabled @endif" data-toggle="tooltip" title="" data-original-title="下一页"><i class="fa fa-chevron-right"></i></a>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="详情">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!-- /.mailbox-controls -->
                <div class="mailbox-read-message">
                    {{ \Illuminate\Mail\Markdown::parse($currentItem->content) }}
                </div>
            </div>
            <div class="box-footer">
                    <ul class="mailbox-attachments clearfix">
                        @foreach(Storage::allFiles('contracts/'.$currentItem->id) as $file)
                        <li>
                            @if(file_type_is_image($file))
                                <span class="mailbox-attachment-icon has-img"><a href="{{ Storage::url($file) }}" data-fancybox="gallery" ><img src="{{ Storage::url($file) }}" style="height: 250px;" alt="{{ basename($file) }}"></a></span>
                                <div class="mailbox-attachment-info">
                                    <span class="mailbox-attachment-name"><i class="fa fa-camera"></i> {{ basename($file) }}</span>
                                    <span class="mailbox-attachment-size">
                                        {{ floor(Storage::size($file)/1000) }} KB
                                        <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                    </span>
                                </div>
                            @else
                                <span class="mailbox-attachment-icon"><i class="fa fa-file fa-file-{{ pathinfo($file, PATHINFO_EXTENSION) }}-o"></i></span>
                                <div class="mailbox-attachment-info">
                                    <a href="{{ Storage::url($file) }}" target="_blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ basename($file) }}</a>
                                    <span class="mailbox-attachment-size">
                                        {{ floor(Storage::size($file)/1000) }} KB
                                        <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                    </span>
                                </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">配置项<span class="small">[合同额:{{ format_currency($currentItem->signed_money) }}]</span></h3>
            </div>
            <div class="box-footer ">
                <form action="{{ route('contracts.update', $currentItem->id) }}" method="post">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="checkbox" id="visible" name="visible" @if($currentItem->visible == 'on') checked @endif>
                                <label for="visible" class="">是否统计</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="payMode">付款方式</label>
                                <div id="payMode" class="input-group col-md-12">
                                    <div class="col-md-4 no-padding">
                                        <input type="radio" name="pay_mode" value="0" @if($currentItem->pay_mode == '按进度') checked @endif>
                                        <label class="">按进度</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="pay_mode" value="1" @if($currentItem->pay_mode == '按部位') checked @endif>
                                        <label class="">按部位</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="advanceAmount" class="row" >
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="advancePaymentAmount">预付款金额</label>
                                <input id="advancePaymentAmount" name="advance_payment_amount" type="text" class="form-control currency " value="{{ $currentItem->advance_payment_amount }}" placeholder="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="progressPayment">进度款比例</label>
                                <input id="progressPayment" name="progress_payment_pct" type="text" class="form-control percent" value="{{ $currentItem->progress_payment_pct }}" placeholder="70%">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-default"><i class="fa fa-floppy-o"></i> 保存</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/fancybox/dist/jquery.fancybox.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/fancybox/dist/jquery.fancybox.min.js') }}"></script>

    <!-- InputMask -->
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/inputmask.numeric.extensions.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/input-mask/jquery.inputmask.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
            $(".percent").inputmask('percentage', {removeMaskOnSubmit: true});
            $(".currency").inputmask('currency', {removeMaskOnSubmit: true, prefix: "¥ ", autoUnmask: true});
        });
    </script>
@endpush
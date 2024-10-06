@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Waiting')</th>
                                <th>@lang('Paid')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($profits as $profit)
                                <tr @if($loop->odd) class="table-light" @endif>
                                    <td data-label="@lang('User')">
                                        <span class="fw-bold">{{$profit->user->fullname}}</span>
                                        <br>
                                        <span class="small">
                                        <a href="{{ route('admin.users.detail', $profit->user_id) }}"><span>@</span>{{ $profit->user->username }}</a>
                                        </span>
                                    </td>
                                    <td data-label="@lang('Amount')">
                                        <span class="fw-bold">{{getAmount($profit->total)}} {{__($profit->currency->code)}}</span>
                                    </td>

                                    <td data-label="@lang('Waiting')">
                                        <span class="text--warning">{{getAmount($profit->total - $profit->success)}} {{__($profit->currency->code)}}</span>
                                    </td>

                                    <td data-label="@lang('Paid')">
                                        <span class="fw-bold text--success">{{getAmount($profit->success)}} {{__($profit->currency->code)}}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="{{route('admin.report.profit')}}" method="GET" class="form-inline float-sm-right bg--white mr-2">
        <div class="input-group has_append">
            <select class="form-control" name="currency">
                <option>----@lang('Select Currency')----</option> 
                @foreach($currencies as $currency)
                    <option value="{{$currency->id}}" @if(@$currencyId == $currency->id) selected="" @endif>{{__($currency->name)}}</option> 
                @endforeach
           </select>
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush


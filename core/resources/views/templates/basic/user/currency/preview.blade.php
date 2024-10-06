@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="card custom--card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-center">
                <h4 class="card-title mb-0">
                    @lang('Activation Fees') : {{getAmount($currency->activation_fees)}} {{__($currency->code)}}
                </h4>
            </div>
            <div class="card-body">
                <div class="card-form-wrapper">
                    <form action="{{route('user.activate.currency.store')}}" method="post" role="form">
                        @csrf
                        <div class="row justify-content-center mb-20-none">
                            <input type="hidden" name="currency_id" value="{{$currency->id}}">
                            @if($currency->user_data)
                                @foreach($currency->user_data as $k => $v)
                                    @if($v->type == "text")
                                        <div class="form-group">
                                            <label class="form--label mb-2">{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</label>
                                            <input type="text" name="{{$k}}" class="form--control" value="{{old($k)}}" @if($v->validation == "required") required @endif>
                                            @if ($errors->has($k))
                                                <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                            @endif
                                        </div>
                                    @elseif($v->type == "textarea")
                                        <div class="form-group">
                                            <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                            <textarea name="{{$k}}"  class="form--control" rows="3" @if($v->validation == "required") required @endif>{{old($k)}}</textarea>
                                            @if ($errors->has($k))
                                                <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                            @endif
                                        </div>
                                    @elseif($v->type == "file")
                                        <div class="form-group">
                                            <label class="form--label mb-2">{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</label>
                                            <input type="file" name="{{$k}}" class="form--control"  @if($v->validation == "required") required @endif>
                                            @if ($errors->has($k))
                                                <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <div class="col-xl-12 form-group">
                                <button type="submit" class="submit-btn w-100">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
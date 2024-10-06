@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card custom--card">
            <div class="card-header">
                <h5 class="card-title">@lang('KYC Form')</h5>
            </div>
            <div class="card-body">
                <form action="{{route('user.kyc.submit')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <x-viser-form identifier="act" identifierValue="kyc" />

                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

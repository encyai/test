@extends($activeTemplate.'layouts.app')
@section('panel')
<section class="ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex justify-content-center mb-5">
                    <img src="{{ getImage('assets/images/maintenance/image.png') }}" alt="maintenance">
                </div>
                @php echo $maintenance->data_values->description @endphp
            </div>
        </div>
    </div>
</section>
@endsection

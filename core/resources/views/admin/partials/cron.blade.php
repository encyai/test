<div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Cron Job Setting Instruction')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="la la-close"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 my-2">
                        <p class="cron-p-style">@lang('To automate merge users investments & withdrawals we have to run the ')<code> @lang('cron job') </code>@lang('. Set the Cron time as minimum as possible. Once per')<code> @lang('1-5') </code>@lang('minutes is ideal').</p>
                    </div>
                    <div class="col-md-12">
                        <label>@lang('Cron Command')</label>
                        <div class="input-group">
                            <input id="cron" type="text" class="form-control form-control-lg" value="curl -s {{route('cron')}}"  readonly="">

                            <button type="btn" id="copybtn" class="btn btn--primary" onclick="myFunction()" >@lang('COPY')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    @if(Carbon\Carbon::parse($general->last_cron_run)->diffInSeconds()>= 300)
        <script>
            'use strict';
            (function($){
                $(window).on('load', function(){
                    $("#myModal").modal('show');
                });
            })(jQuery)
            function myFunction() {
                var copyText = document.getElementById("cron");
                copyText.select();
                copyText.setSelectionRange(0, 99999999)
                document.execCommand("copy");
                notify('success', 'Url copied successfully ' + copyText.value);
            }
        </script>
    @endif
@endpush
@extends($activeTemplate.'layouts.master')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card custom--card">
            <div class="card-header card-header-bg d-flex flex-wrap justify-content-between align-items-center">
                <h4 class="text-white mb-0">{{__($pageTitle)}}</h4>
                <a href="{{route('ticket.index') }}" class="btn btn--base text-white">
                    @lang('My Support Ticket')
                </a>
            </div>
            <div class="card-body">
                <form  action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">@lang('Name')</label>
                            <input type="text" name="name" value="{{@$user->firstname . ' '.@$user->lastname}}" class="form-control form--control form-control-lg" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">@lang('Email address')</label>
                            <input type="email"  name="email" value="{{@$user->email}}" class="form-control form--control form-control-lg" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="website">@lang('Subject')</label>
                            <input type="text" name="subject" value="{{old('subject')}}" class="form-control form--control form-control-lg">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="priority">@lang('Priority')</label>
                            <select name="priority" class="form-select form--control form-control-lg">
                                <option value="3">@lang('High')</option>
                                <option value="2">@lang('Medium')</option>
                                <option value="1">@lang('Low')</option>
                            </select>
                        </div>

                        <div class="col-12 form-group">
                            <label for="inputMessage">@lang('Message')</label>
                            <textarea name="message" id="inputMessage" rows="5" class="form-control form--control form-control-lg" required>{{old('message')}}</textarea>
                        </div>
                    </div>

                    
                    <div class="row justify-content-between">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="text-end">
                                    <button type="button" class="btn btn--base btn-sm addFile">
                                        <i class="la la-plus"></i> @lang('Add New')
                                    </button>
                                </div>
                                <label class="form-label d-inline">@lang('Attachments')</label> <small class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                <input type="file" name="attachments[]" id="inputAttachments" class="form-control form--control mt-1"/>
                                <div id="fileUploadsContainer"></div>
                                <p class="my-2 ticket-attachments-message text-muted">
                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                </p>
                            </div>
                        </div>
                    </div>
                        

                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn--base w-100"><i class="la la-paper-plane"></i> @lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click',function(){
                if (fileAdded >= 4) {
                    notify('error','You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required />
                        <button type="button" class="input-group-text btn btn--danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click','.remove-btn',function(){
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush

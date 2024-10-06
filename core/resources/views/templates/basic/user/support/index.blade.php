@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="text-end mb-2">
        <a href="{{ route('ticket.open') }}" class="btn btn-md btn--base">
            <i class="la la-plus"></i> @lang('New Ticket')
        </a>
    </div>
    <table class="custom-table">
        <thead>
            <tr>
                <th>@lang('Subject')</th>
                <th>@lang('Status')</th>
                <th>@lang('Priority')</th>
                <th>@lang('Last Reply')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($supports as $key => $support)
                <tr>
                    <td><a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                    <td>
                        @php echo $support->statusBadge; @endphp
                    </td>
                    <td>
                        @if ($support->priority == 1)
                            <span class="badge badge--dark">@lang('Low')</span>
                        @elseif($support->priority == 2)
                            <span class="badge badge--success">@lang('Medium')</span>
                        @elseif($support->priority == 3)
                            <span class="badge badge--primary">@lang('High')</span>
                        @endif
                    </td>
                    <td>{{ now()->parse($support->last_reply)->diffForHumans() }} </td>

                    <td>
                        <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn--base btn-sm">
                            <i class="la la-desktop"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center data-not-found">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

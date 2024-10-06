@extends($activeTemplate . 'layouts.master')
@section('content')
    <table class="custom-table">
        <thead>
            <tr>
                <th>@lang('Invested at')</th>
                <th>@lang('Invested Amount')</th>
                <th>@lang('Profit')</th>
                <th>@lang('Withdrawable Amount')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($investments as $investment)
                <tr>
                    <td>
                        <span>{{ diffForHumans($investment->created_at) }}</span><br>
                        <span>{{ showDateTime($investment->created_at) }}</span>
                    </td>
                    <td>
                        {{ getAmount($investment->amount) }} {{ __($investment->currency->code) }}
                    </td>
                    <td>
                        {{ getAmount($investment->profit) }} {{ __($investment->currency->code) }}
                    </td>
                    <td>
                        {{ getAmount($investment->withdrawable_amount) }} {{ __($investment->currency->code) }}
                    </td>

                    <td>
                        <a href="{{ route('user.withdraw.store', encrypt($investment->id . '|' . auth()->user()->id)) }}" class="btn btn--base">
                            <i class="fa fa-desktop"></i>
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
    {{ $investments->links() }}
@endsection

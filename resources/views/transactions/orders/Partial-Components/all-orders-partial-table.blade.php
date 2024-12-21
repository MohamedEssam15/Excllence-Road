@php
    $i = 1;
@endphp
@if (!isset($orders[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('translation.noOrders')</td>
    </tr>
@else
    @foreach ($orders as $order)
        <tr>
            <th scope="row">
                {{ $i++ }}
            </th>
            <td>
                <span class="text-body">{{ $order->order_number }}</span>
            </td>
            <td>{{ $order->payment->amount }}</td>
            <td>
                <span class="text-body">{{ $order->student->name }}</span>
            </td>
            <td>
                @if ($order->is_package)
                    <span class="text-body">@lang('translation.package')</span>
                @else
                    <span class="text-body">@lang('translation.course')</span>
                @endif

            </td>
            <td>
                <span class="text-body">{{ $order->product->translate()->name }}</span>
            </td>
            <td>
                <span class="text-body">{{ $order->created_at->format('Y-m-d g:i A') }}</span>
            </td>
            <td>
                <span class="text-body">{{ $order->discount ? $order->discount : '-' }}</span>
            </td>
            <td>
                @if ($order->discount_type == 'percentage')
                    <span class="text-body">@lang('translation.percentage')</span>
                @elseif ($order->discount_type == 'fixedPrice')
                    <span class="text-body">@lang('translation.fixedPrice')</span>
                @else
                    <span class="text-body">-</span>
                @endif
            </td>
            <td>
                <span class="text-body">{{ $order->addedBy?->name ?? '-' }}</span>
            </td>
            <td>
                @if ($order->payment->status == 'done')
                    <span class="badge bg-success-subtle text-success">@lang('translation.completed')</span>
                @else
                    <span class="badge bg-danger-subtle text-danger">@lang('translation.failed')</span>
                @endif
            </td>
        </tr>
    @endforeach
@endif

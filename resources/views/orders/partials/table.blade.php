@forelse ($orders as $order)
    @php
        $servicesTotalCost = 0;
        foreach ($order->orderServices as $service) {
            $servicesTotalCost = $servicesTotalCost + $service->cost;
        }
    @endphp
    <tr class="clickable" onclick="window.location='/orders/{{ $order->id }}';" style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';">
        <td style="width: 10%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $order->number }}
        </td>
        <td style="width: 20%; padding: 1rem 1.25rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600;">
                @if($order->company)
                    {{ $order->company->name }}
                @elseif($order->person)
                    {{ $order->person->name }} (Person)
                @else
                    -
                @endif
            </strong>
        </td>
        <td style="width: 20%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $order->name }}
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($order->status == 'Active')
                <span style="color: darkgoldenrod; font-weight: 600;">{{ $order->status }}</span>
            @elseif($order->status == 'Not Active')
                <span style="color: red; font-weight: 600;">{{ $order->status }}</span>
            @elseif($order->status == 'Finished')
                <span style="color: green; font-weight: 600;">{{ $order->status }}</span>
            @else
                <span style="color: #374151;">{{ $order->status }}</span>
            @endif
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($order->payment_status == 'Partially paid')
                <span style="color: darkgoldenrod; font-weight: 600;">{{ $order->payment_status }}</span>
            @elseif($order->payment_status == 'Not Paid')
                <span style="color: red; font-weight: 600;">{{ $order->payment_status }}</span>
            @elseif($order->payment_status == 'Paid')
                <span style="color: green; font-weight: 600;">{{ $order->payment_status }}</span>
            @else
                <span style="color: #374151;">{{ $order->payment_status }}</span>
            @endif
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ number_format($servicesTotalCost, 2) }} EUR
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            <i class="fa-solid fa-user-tie" style="color: #DC2626; margin-right: 0.5rem;"></i>{{ $order->responsible_user->name ?? '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No orders found</span>
        </td>
    </tr>
@endforelse


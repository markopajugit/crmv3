@forelse ($invoices as $invoice)
    <tr class="clickable" onclick="window.location='/view/pdf/{{ $invoice->id }}';" style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';">
        <td style="width: 10%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $invoice->id }}
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $invoice->number }}
        </td>
        <td style="width: 20%; padding: 1rem 1.25rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600;">
                @if($invoice->order->company)
                    {{ $invoice->order->company->name }}
                @elseif($invoice->order->person)
                    {{ $invoice->order->person->name }}
                @else
                    -
                @endif
            </strong>
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d.m.Y') : '-' }}
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('d.m.Y') : '-' }}
        </td>
        <td style="width: 10%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $invoice->vat }}%
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $invoice->order->name ?? '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No invoices found</span>
        </td>
    </tr>
@endforelse


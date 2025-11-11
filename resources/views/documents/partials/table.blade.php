@forelse ($files as $file)
    <tr style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';">
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($file->archive_nr)
                <span style="color: #6B7280; font-weight: 500;">Archive</span>
            @elseif($file->virtual_office)
                <span style="color: #6B7280; font-weight: 500;">Virtual Office</span>
            @else
                <span style="color: #6B7280; font-weight: 500;">General</span>
            @endif
        </td>
        <td style="width: 30%; padding: 1rem 1.25rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600;">{{ $file->name }}</strong>
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $file->archive_nr ?? '-' }}
        </td>
        <td style="width: 25%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($file->person_id)
                <a href="/persons/{{ $file->person_id }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">
                    <i class="fa-solid fa-user" style="margin-right: 0.5rem;"></i>Person
                </a>
            @elseif($file->company_id)
                <a href="/companies/{{ $file->company_id }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">
                    <i class="fa-solid fa-building" style="margin-right: 0.5rem;"></i>Company
                </a>
            @elseif($file->order_id)
                <a href="/orders/{{ $file->order_id }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">
                    <i class="fa-solid fa-file-invoice" style="margin-right: 0.5rem;"></i>Order
                </a>
            @else
                <span class="text-muted" style="color: #9CA3AF;">-</span>
            @endif
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $file->created_at ? \Carbon\Carbon::parse($file->created_at)->format('d.m.Y') : '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No documents found</span>
        </td>
    </tr>
@endforelse


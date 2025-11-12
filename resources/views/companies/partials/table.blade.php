@forelse ($companies as $company)
    <tr class="clickable" onclick="window.location='/companies/{{ $company->id }}';" style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';">
        <td style="width: 25%; padding: 1rem 1.25rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600;">{{ $company->name }}</strong>
        </td>
        <td style="width: 20%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $company->registry_code }}
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $company->registration_country }}
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $company->vat }}
        </td>
        <td style="width: 25%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($company->email)
                <a href="mailto:{{ $company->email }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">{{ $company->email }}</a>
            @else
                <span class="text-muted" style="color: #9CA3AF;">-</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No companies found</span>
        </td>
    </tr>
@endforelse


@forelse ($companies as $company)
    <tr class="clickable" onclick="window.location='/companies/{{ $company->id }}';" style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';">
        <td style="width: 25%; padding: 1rem 1.25rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600;">{{ $company->name }}</strong>
            @if($company->address_street || $company->address_city)
                <br><small class="text-muted" style="color: #6B7280; font-size: 0.75rem;">
                    {{ $company->address_street }}
                    @if($company->address_street && $company->address_city), @endif
                    {{ $company->address_city }}
                    @if($company->address_zip) {{ $company->address_zip }} @endif
                </small>
            @endif
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $company->registry_code ?: '-' }}
        </td>
        <td style="width: 12%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $company->registration_country ?: '-' }}
            @if($company->registration_country_abbr && $company->registration_country_abbr !== 'N/A')
                <br><small class="text-muted" style="color: #6B7280; font-size: 0.75rem;">({{ $company->registration_country_abbr }})</small>
            @endif
        </td>
        <td style="width: 12%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $company->vat ?: '-' }}
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($company->email)
                <a href="mailto:{{ $company->email }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">{{ $company->email }}</a>
            @else
                <span class="text-muted" style="color: #9CA3AF;">-</span>
            @endif
        </td>
        <td style="width: 10%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($company->phone)
                <a href="tel:{{ $company->phone }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">{{ $company->phone }}</a>
            @else
                <span class="text-muted" style="color: #9CA3AF;">-</span>
            @endif
        </td>
        <td style="width: 11%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($company->getCurrentRisk)
                @php
                    $risk = $company->getCurrentRisk->risk_level ?? 'Unknown';
                    $badgeClass = match(strtolower($risk)) {
                        'low' => 'bg-success',
                        'medium' => 'bg-warning',
                        'high' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ ucfirst($risk) }}</span>
            @else
                <span class="badge bg-secondary">Unknown</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No companies found</span>
        </td>
    </tr>
@endforelse


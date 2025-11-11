@forelse ($persons as $person)
    <tr class="clickable" onclick="window.location='/persons/{{ $person->id }}';" style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';">
        <td style="width: 20%; padding: 1rem 1.25rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600;">{{ $person->name }}</strong>
            @if($person->address_street || $person->address_city)
                <br><small class="text-muted" style="color: #6B7280; font-size: 0.75rem;">
                    {{ $person->address_street }}
                    @if($person->address_street && $person->address_city), @endif
                    {{ $person->address_city }}
                    @if($person->address_zip) {{ $person->address_zip }} @endif
                </small>
            @endif
        </td>
        <td style="width: 12%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $person->id_code ?: '-' }}
        </td>
        <td style="width: 10%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $person->country ?: '-' }}
        </td>
        <td style="width: 15%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($person->email)
                <a href="mailto:{{ $person->email }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">{{ $person->email }}</a>
            @else
                <span class="text-muted" style="color: #9CA3AF;">-</span>
            @endif
        </td>
        <td style="width: 12%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($person->phone)
                <a href="tel:{{ $person->phone }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">{{ $person->phone }}</a>
            @else
                <span class="text-muted" style="color: #9CA3AF;">-</span>
            @endif
        </td>
        <td style="width: 10%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $person->date_of_birth ?: '-' }}
        </td>
        <td style="width: 10%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($person->pep === 1)
                <span class="badge bg-warning">PEP</span>
            @elseif($person->pep === 0)
                <span class="badge bg-success">Not PEP</span>
            @else
                <span class="badge bg-secondary">Unknown</span>
            @endif
        </td>
        <td style="width: 11%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($person->getCurrentRisk)
                @php
                    $risk = $person->getCurrentRisk->risk_level ?? 'Unknown';
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
        <td colspan="8" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No persons found</span>
        </td>
    </tr>
@endforelse


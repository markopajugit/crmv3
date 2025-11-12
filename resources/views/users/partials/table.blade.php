@forelse ($users as $user)
    <tr class="clickable" onclick="window.location='/users/{{ $user->id }}';" style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';">
        <td style="width: 10%; padding: 1rem 1.25rem; vertical-align: middle; color: #6B7280;">
            {{ $user->id }}
        </td>
        <td style="width: 35%; padding: 1rem 1.25rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600;">{{ $user->name }}</strong>
        </td>
        <td style="width: 35%; padding: 1rem 1.25rem; vertical-align: middle;">
            @if($user->email)
                <a href="mailto:{{ $user->email }}" style="color: #DC2626; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#B91C1C';" onmouseout="this.style.color='#DC2626';">{{ $user->email }}</a>
            @else
                <span class="text-muted" style="color: #9CA3AF;">-</span>
            @endif
        </td>
        <td style="width: 20%; padding: 1rem 1.25rem; vertical-align: middle; color: #374151;">
            {{ $user->created_at ? $user->created_at->format('d.m.Y') : '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No users found</span>
        </td>
    </tr>
@endforelse


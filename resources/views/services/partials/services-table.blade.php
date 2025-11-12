@forelse ($services as $service)
    <tr style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';" onclick="window.location='/services/{{ $service->id }}';">
        <td style="padding: 0.75rem 0.875rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600; font-size: 0.875rem;">{{ $service->name }}</strong>
            @if($service->service_category)
                <br><small style="color: #6B7280; font-size: 0.75rem;">
                    <i class="fa-solid fa-folder" style="margin-right: 0.25rem;"></i>{{ $service->service_category->name }}
                </small>
            @endif
        </td>
        <td style="padding: 0.75rem 0.875rem; vertical-align: middle; color: #374151; font-size: 0.875rem;">
            {{ $service->cost ? number_format($service->cost, 2) . ' EUR' : '-' }}
        </td>
        <td style="padding: 0.75rem 0.875rem; vertical-align: middle; color: #374151; font-size: 0.875rem;">
            {{ $service->type ?? '-' }}
            @if($service->type == 'Reaccuring' && $service->reaccuring_frequency)
                <br><small style="color: #6B7280; font-size: 0.75rem;">{{ $service->reaccuring_frequency }}mo</small>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No services found</span>
        </td>
    </tr>
@endforelse


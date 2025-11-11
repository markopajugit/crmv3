@forelse ($service_categories as $category)
    <tr style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f3f4f6;" onmouseover="this.style.backgroundColor='#f9fafb';" onmouseout="this.style.backgroundColor='#ffffff';" onclick="window.location='/services/category/{{ $category->id }}';">
        <td style="padding: 0.75rem 0.875rem; vertical-align: middle;">
            <strong style="color: #1f2937; font-weight: 600; font-size: 0.875rem;">{{ $category->name }}</strong>
        </td>
        <td style="padding: 0.75rem 0.875rem; vertical-align: middle; color: #374151;">
            <span style="background-color: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">
                {{ $category->services_count ?? 0 }}
            </span>
        </td>
        <td style="padding: 0.75rem 0.875rem; vertical-align: middle; color: #374151; font-size: 0.875rem;">
            {{ $category->created_at ? \Carbon\Carbon::parse($category->created_at)->format('d.m.Y') : '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="text-center text-muted py-5" style="padding: 3rem 1.25rem; color: #6B7280;">
            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
            <span style="font-size: 0.875rem;">No categories found</span>
        </td>
    </tr>
@endforelse


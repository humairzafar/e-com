@if(!empty($subCategories))

    @foreach($subCategories as $index => $subCategory)
    <tr>
        <td scope="row">
            <div class="form-check">
                <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
            </div>
        </td>
        <td>{{ $index + 1 }}</td>
        <td>{{ $subCategory->category->name ?? 'N/A' }}</td>
        <td>{{ $subCategory->name ?? 'N/A' }}</td>
        <td>{{ $subCategory->slug ?? 'N/A' }}</td>
        <td>
            <span class="badge bg-info-subtle text-info">{{ $subCategory->is_active ? 'Active' : 'Inactive' }}</span>
        </td>
        <td>{{ $subCategory->created_at->format('d M, Y') ?? 'N/A' }}</td>
        <td>{{ $subCategory->updated_at->format('d M, Y') ?? 'N/A' }}</td>

        <td>
            <div class="dropdown d-inline-block">
                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-fill align-middle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a href="" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                        <li><a class="dropdown-item edit-item-btn" data-record-id="{{ $subCategory->id }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                    <li>
                        <a class="dropdown-item remove-item-btn" data-record-id="{{ $subCategory->id }}">
                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="9" class="text-center">No sub-categories found</td>
    </tr>
@endif

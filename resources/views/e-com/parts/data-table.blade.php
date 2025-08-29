@if(!empty($parts))
    @foreach($parts as $index => $part)
    <tr>
        <th scope="row">
            <div class="form-check">
                <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
            </div>
        </th>
        <td>{{ $index + 1 }}</td>
        <td>{{ $part->name ?? 'N/A' }}</td>
        <td>{{ $part->slug ?? 'N/A' }}</td>
        <td>
            <span class="badge bg-info-subtle text-info">{{ $part->is_active ? 'Active' : 'Inactive' }}</span>
        </td>
        <td>{{ $part->location->name ?? 'N/A'  }}</td>
        {{-- <td>{{ $part->location->name }}</td> --}}
        <td>{{ $part->created_at->format('d M, Y') ?? 'N/A' }}</td>
        <td>{{ $part->updated_at->format('d M, Y') ?? 'N/A' }}</td>

        <td>
            <div class="dropdown d-inline-block">
                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-fill align-middle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a href="" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                    @can('edit-part')
                    <li><a class="dropdown-item edit-item-btn" data-record-id="{{ $part->id }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                    @endcan
                    @can('delete-part')
                    <li>
                        <a class="dropdown-item remove-item-btn" data-record-id="{{ $part->id }}">
                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </td>
    </tr>
    @endforeach
@endif

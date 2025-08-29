@if(!empty($users))

    @foreach($users as $index => $user)
    <tr>
        <td scope="row">
            <div class="form-check">
                <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
            </div>
        </td>
        <td>{{ $index + 1 }}</td>
        <td>{{ $user->name ?? 'N/A' }}</td>
        <td>{{ $user->email ?? 'N/A' }}</td>
        <td>{{ $user->role->name ?? 'No Role' }}</td>
        {{-- <td>{{ $user->getAllpermissions()->pluck('name')->join(', ') }}</td> --}}
        <td>
            <span class="badge bg-info-subtle text-info">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
        </td>
        <td>{{ $user->created_at->format('d M, Y') ?? 'N/A' }}</td>
        <td>{{ $user->updated_at->format('d M, Y') ?? 'N/A' }}</td>
        <td>
            <div class="dropdown d-inline-block">
                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-fill align-middle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @can('view-user')
                    <li><a href="" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                    @endcan
                    @can('edit-user')
                        <li><a class="dropdown-item edit-item-btn" data-record-id="{{ $user->id }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                        </li>
                    @endcan
                    @can('delete-user')
                    <li>
                        <a class="dropdown-item remove-item-btn" data-record-id="{{ $user->id }}">
                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </td>
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="9" class="text-center">No users found</td>
    </tr>
@endif

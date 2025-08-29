@if(!empty($roles))
    @foreach($roles as $index => $role)
    <tr>
        {{-- <td scope="row">
            <div class="form-check">
                <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
            </div>
        </td> --}}
        <td>{{ $index + 1 }}</td>
        {{-- <td>
            {{-- @if($role->image_url)
            <img src="{{ asset('storage/'.$role->image_url) }}" alt="role Image" class="rounded-circle" width="50" height="50">
            @else
            <img src="{{ asset('images/default-role.png') }}" alt="Default Image" class="rounded-circle" width="50" height="50">
            @endif --}}

        {{-- <img src="{{ $role->image_url ?? '' }}" alt="role Image" class="rounded-circle" width="50" height="50">
      </td> --}}
        <td>{{ $role->name ?? 'N/A' }}</td>
        <td>@if($role->permissions->count() > 0)
                @foreach($role->permissions as $permission)
                {{ $permission->name }}
                @endforeach
                @else
                <span class="text-muted">No permissions assigned</span>
                @endif</td>
        <td>{{ $role->created_at->format('d M, Y') ?? 'N/A' }}</td>
        <td>{{ $role->updated_at->format('d M, Y') ?? 'N/A' }}</td>
        <td>
            <div class="dropdown d-inline-block">
                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-fill align-middle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a href="" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                    @can('edit-roles')
                        <li><a class="dropdown-item edit-item-btn" data-record-id="{{ $role->id }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                        </li>
                    @endcan
                    @can('delete-roles')
                    <li>
                        <a class="dropdown-item remove-item-btn" data-record-id="{{ $role->id }}">
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
        <td colspan="9" class="text-center">No roles found</td>
    </tr>
@endif

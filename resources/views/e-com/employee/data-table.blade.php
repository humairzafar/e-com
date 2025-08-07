@foreach ($employees as $emp)
<tr>
    <td>
        <div class="form-check">
            <input class="form-check-input fs-15" type="checkbox">
        </div>
    </td>
    <td>{{ $loop->iteration }}</td>
    <td><img src="{{ asset($emp->image) }}" width="100"></td>
    <td>{{ $emp->firstname }}</td>
    <td>{{ $emp->lastname }}</td>
    <td>{{ $emp->cnic }}</td>
    <td>{{ $emp->dob }}</td>
    <td>{{ $emp->doj }}</td>
    <td>{{ $emp->department->name ?? 'N/A' }}</td>
    <td>{{ $emp->designation->name ?? 'N/A' }}</td>
    <td>
        @if($emp->is_active)
            <span class="badge bg-success">Active</span>
        @else
            <span class="badge bg-danger">Inactive</span>
        @endif
    </td>
<td>
            <div class="dropdown d-inline-block">
                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-fill align-middle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a href="" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                    <li><a class="dropdown-item edit-item-btn"  data-record-id="{{ $emp->id }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                    <li>
                        <a class="dropdown-item remove-item-btn" data-record-id="{{ $emp->id }}">
                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
</tr>
@endforeach

@foreach ($departments as $dept)
<tr>
    <td>
        <div class="form-check">
            <input class="form-check-input fs-15" type="checkbox">
        </div>
    </td>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $dept->name }}</td>
    <td>{{ $dept->is_head_office_department ? 'Yes' : 'No' }}</td>
    <td>
        @if($dept->is_active)
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
                    @can('edit-department')
                    <li><a class="dropdown-item edit-item-btn"  data-record-id="{{ $dept->id }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                    @endcan
                    @can('delete-department')
                    <li>
                        <a class="dropdown-item remove-item-btn" data-record-id="{{ $dept->id }}">
                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </td>
</tr>
@endforeach

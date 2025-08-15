@if(!empty($vehicles))
    @foreach($vehicles as $index => $vehicle)
    <tr>
        <th scope="row">
            <div class="form-check">
                <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
            </div>
        </th>
        <td>{{ $index + 1 }}</td>
        <td>{{ $vehicle->vehicle_id ?? 'N/A' }}</td>
        <td>{{ $vehicle->category?->name ?? 'N/A' }}</td>
        <td>{{ $vehicle->town?->name ?? 'N/A' }}</td>
        <td>
            <span class="badge bg-info-subtle text-info">{{ $vehicle->condition ? 'New' : 'Old' }}</span>
        </td>
        <td>
            <span class="badge bg-info-subtle text-info">{{ $vehicle->condition ? 'Yes' : 'Now' }}</span>
        </td>
        {{-- <td>{{ $vehicle->location->name }}</td> --}}
        <td>{{ $vehicle->created_at->format('d M, Y') ?? 'N/A' }}</td>
        <td>{{ $vehicle->updated_at->format('d M, Y') ?? 'N/A' }}</td>

        <td>
            <div class="dropdown d-inline-block">
                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-fill align-middle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a href="" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                    <li><a class="dropdown-item edit-item-btn" data-record-id="{{ $vehicle->id }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                    <li>
                        <a class="dropdown-item remove-item-btn" data-record-id="{{ $vehicle->id }}">
                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    @endforeach
@endif

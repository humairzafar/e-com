@if(!empty($products))

    @foreach($products as $index => $product)
    <tr>
        <td scope="row">
            <div class="form-check">
                <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
            </div>
        </td>
        <td>{{ $index + 1 }}</td>
        <td>
            {{-- @if($product->image_url)
            <img src="{{ asset('storage/'.$product->image_url) }}" alt="Product Image" class="rounded-circle" width="50" height="50">
            @else
            <img src="{{ asset('images/default-product.png') }}" alt="Default Image" class="rounded-circle" width="50" height="50">
            @endif --}}

        <img src="{{ $product->image_url ?? '' }}" alt="Product Image" class="rounded-circle" width="50" height="50">
      </td>
        <td>{{ $product->name ?? 'N/A' }}</td>
        <td>{{ $product->sku ?? 'N/A' }}</td>
        <td>{{ $product->category->name ?? 'N/A' }}</td>
        <td>{{ $product->subCategory->name ?? 'N/A' }}</td>
        <td>{{ $product->unit_cost_price ?? 'N/A' }}</td>
        <td>{{ $product->price ?? 'N/A' }}</td>
        <td>{{ $product->quantity ?? 'N/A' }}</td>
        <td>
            <span class="badge bg-info-subtle text-info">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
        </td>
        <td class="description-cell" title="{{ $product->description }}">
            {{ Str::limit($product->description, 10) }}</td>
        <td>{{ $product->created_at->format('d M, Y') ?? 'N/A' }}</td>
        <td>{{ $product->updated_at->format('d M, Y') ?? 'N/A' }}</td>

        <td>
            <div class="dropdown d-inline-block">
                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-fill align-middle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @can('view-product')
                    <li><a href="" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                    @endcan
                    @can('edit-product')
                        <li><a class="dropdown-item edit-item-btn" data-record-id="{{ $product->id }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                        </li>
                    @endcan
                    @can('delete-product')
                    <li>
                        <a class="dropdown-item remove-item-btn" data-record-id="{{ $product->id }}">
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
        <td colspan="9" class="text-center">No products found</td>
    </tr>
@endif

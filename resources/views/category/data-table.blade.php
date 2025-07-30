@if(!@empty($categories))
    @foreach ($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->slug }}</td>
            <td>
                <button class="btn btn-sm btn-success btn-edit" id="js-edit-btn" data-id="{{ $category->id }}">Edit</button>
                <button class="btn btn-sm btn-danger btn-delete" id="js-dlt-btn" data-id="{{ $category->id }}">Delete</button>
            </td>
        </tr>
    @endforeach
@endempty

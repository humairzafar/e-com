@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <div id="alert-msg" class="alert d-none"></div>
<!-- Modal -->
<div class="container mt-3">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
    Add Category
  </button>
</div>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-center">Add New Category</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="{{ route('store') }}">
          @csrf
         <input type="hidden" id="category_id">
          <div class="mb-3">
            <label>Category Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Slug</label>
            <input type="text" id="slug" name="slug" class="form-control" readonly>
          </div>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Add Category</button>
      </form>
      </div>

    </div>
  </div>
</div>
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Category Slug</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                 <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        function showAlert(message, type = 'success') {
        $('#alert-msg')
            .removeClass('d-none')
            .removeClass('alert-success alert-danger')
            .addClass('alert-' + type)
            .text(message);

        setTimeout(() => {
            $('#alert-msg').addClass('d-none');
        }, 3000);
    }
    </script>
    <script>
        $('#name').on('input', function() {
        let slug = $(this).val().toLowerCase()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-');
        $('#slug').val(slug);
    });
    </script>
@endsection

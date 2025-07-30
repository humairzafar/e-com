@extends('layout.app')

@section('content')
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
        <form method="POST" action="{{ route('store') }}" id="js-categoryform">
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
    <table id="js-categoryTable" class="display">
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Category Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="js-category-dataTable">
            @include('category.data-table')
        </tbody>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#js-categoryTable').DataTable();


            //edit record starts here

            $('#js-edit-btn').on('click', function(){
                var catId = $(this).data('id');
                $.ajax({
                url: 'category/edit',
                type: 'POST',
                beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                data: {id :catId},
                success: function(response) {
                    if(response){
                        $('#name').val(response.name)
                        $('#slug').val(response.slug)
                    }
                    $('#myModal').modal('show');
            }});

        });

            //edit record ends here





            //delete record starts here

            $('#js-dlt-btn').on('click', function(){
                console.log('delete button clicked');
            });

            //delete record ends here




        });

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
        $('#name').on('input', function() {
        let slug = $(this).val().toLowerCase()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-');
        $('#slug').val(slug);
    });


        $('#js-categoryform').on('submit', function(e) {
            console.log('abcd');
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response) {
                        $('#myModal').modal('hide');
                        // // Update table
                        if(response.success)
                        {
                            $('#js-categoryTable').html();
                            $('#js-categoryTable').html(response.html);

                        }
                        // // Reset form
                        $('#categoryform')[0].reset();

                        // Show success message
                        showSuccessAlert(response.message, 1500);
                        location.reload();
                    } else {
                        showErrorAlert("Operation failed");
                    }
                },
                error: function(xhr) {
                    // Show error message
                    let errorMessage = "Something went wrong. Please try again.";

                    // If there are validation errors, show them
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                }
            });
        });
    </script>
@endsection

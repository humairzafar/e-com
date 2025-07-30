@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" id="abc" >
                <h3 class="card-title mb-0">Categories</h3>

                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-category-modal">Add Category</button>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">

                            <div class="card-body">
                                <table id="js-categories-datatables" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <tbody id="js-categories-datatables-body">
                                            @include('e-com.categories.data-table')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!--end col-->
                </div>
            </div>
        </div>
    </div>
</div>






<!--modal starts here-->

<!--modal starts here-->
<div id="js-add-category-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="category-form">
                    @csrf
                    <input type="hidden" name="id" id="js-category-id">
                    <div class="mb-3">
                        <label for="category-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="category-name" placeholder="Enter category name">
                    </div>
                    <div class="mb-3">
                        <label for="category-slug" class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" id="category-slug" placeholder="Category slug" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="category-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="category-status">
                            <option value="" disabled selected>Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary" id="form-submit-btn">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--modal ends here-->
<!--modal ends here-->

@endsection


@section('scripts')
<script>
  $(document).ready(function() {
    // Initialize DataTable
    $('#js-categories-datatables').DataTable();

    // Handle form submission for both add starts here
    $('#category-form').on('submit', function(e) {
        e.preventDefault();

        // Check if the form is valid using jQuery validator
        if (!$('#category-form').valid()) {
            return false;
        }

        const formData = $(this).serialize();
        const isUpdate = $('#js-category-id').val() !== '';
        const url = isUpdate ? "{{ route('categories.update') }}" : "{{ route('categories.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {
                $('#js-add-category-modal').modal('hide');
                $('#category-form')[0].reset();
                $('#js-category-id').val('');
                $('#js-categories-datatables-body').html(response.html);
                Swal.fire('Success', response.message, 'success');
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong', 'error');
            },
            complete: function() {
                $('#form-submit-btn').prop('disabled', false);
            }
        });
    });
    // Handle form submission for both add ends here
    // Edit category
    $(document).on('click', '.edit-item-btn', function(e) {
        e.preventDefault();
        const recordId = $(this).data('record-id');

        $.ajax({
            url: "{{ route('categories.edit') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: recordId
            },
            success: function(response) {
                $('#modal-title').text('Edit Category');
                $('#js-category-id').val(response.id);
                $('#category-name').val(response.name);
                $('#category-slug').val(response.slug);
                $('#category-status').val(response.is_active);
                $('#form-submit-btn').text('Update');
                $('#js-add-category-modal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load category data', 'error');
            }
        });
    });

    // Delete category
    $(document).on('click', '.remove-item-btn', function(e) {
        e.preventDefault();
        const recordId = $(this).data('record-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('categories.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        $('#js-categories-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete category', 'error');
                    }
                });
            }
        });
    });

    // Reset modal when closed
    $('#js-add-category-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add Category');
        $('#form-submit-btn').text('Add');
        $('#category-form')[0].reset();
        $('#js-category-id').val('');
        // 3. Reset validation state
        const validator = $('#category-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#category-form').find('.is-invalid').removeClass('is-invalid');

    });

    // Auto-generate slug when name changes
    $('#category-name').on('input', function() {
        const name = $(this).val();
        $('#category-slug').val(name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });

    $('#category-form').validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            slug: {
                required: true,
                minlength: 3
            },
            is_active: {
                required: true,
            }
        },
        // hightlight:function(element){
        //     $(element).addClass('red-border');
        // },
        // unhighlight:function(element){
        //     $(element).removeClass('red-border');
        // },
        messages: {
            name: {
                required: "Name is required",
                minlength: "Name must be at least 3 characters long"
            },
            slug: {
                required: "Slug is required",
                minlength: "Slug must be at least 3 characters long"
            },
            is_active: {
                required: "Status is required",
            }
        }
    });
});

</script>
@endsection

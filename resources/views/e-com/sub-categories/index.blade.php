@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Sub-Categories</h3>
                @can('add-sub-category')
                <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-sub-category-modal">Add Sub-Category</button>
                @endcan

            </div>
            <div class="card-header">"
            <a href="{{ route('sub-categories.exportCsv') }}" class="btn btn-success">Export Categories (CSV)</a>
            <form action="{{ route('sub-categories.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" required>
                <button type="submit" class="btn btn-success">Import Categories</button>
            </form>
        </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

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
                                            <th>Category Name</th>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <tbody id="js-sub-categories-datatables-body">
                                            @include('e-com.sub-categories.data-table', ['subCategories' => $subCategories])
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
<div id="js-add-sub-category-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add Sub-Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="sub-category-form">
                    @csrf
                    <input type="hidden" name="id" id="js-sub-category-id">
                    <div class="mb-3">
                        <label for="sub-category-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="sub-category-name" placeholder="Enter sub-category name">
                    </div>
                    <div class="mb-3">
                        <label for="sub-category-slug" class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" id="sub-category-slug" placeholder="Sub-Category slug" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="sub-category-category" class="form-label">Category</label>
                        <select class="form-control" name="category_id" id="js-category-dropdown" required>
                            <option value="" disabled selected>Select Category</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sub-category-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="sub-category-status">
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
    $('#js-add-sub-category-modal').on('shown.bs.modal', function() {
        getAllCategories();
    });
    $('#js-add-sub-category-modal').on('hidden.bs.modal', function() {
        new FormData($('#sub-category-form')[0].reset());
    });

    // Handle form submission for both add starts here
    $('#sub-category-form').on('submit', function(e) {
        e.preventDefault();

        // Check if the form is valid using jQuery validator
        if (!$('#sub-category-form').valid()) {
            return false;
        }

        const formData = $(this).serialize();
        const isUpdate = $('#js-sub-category-id').val() !== '';
        const url = isUpdate ? "{{ route('sub-categories.update') }}" : "{{ route('sub-categories.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
            success: function(response) {
                $('#js-add-sub-category-modal').modal('hide');
                $('#sub-category-form')[0].reset();
                $('#js-sub-category-id').val('');
                $('#js-sub-categories-datatables-body').html(response.html);
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

        // getAllCategories();

        $.ajax({
            url: "{{ route('sub-categories.edit') }}",
            method: 'POST',
            data: {
                id: recordId
            },
            beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function({data : response}) {
                $('#modal-title').text('Edit Sub-Category');
                $('#js-sub-category-id').val(response.id);
                 $('#sub-category-name').val(response.name);
                $('#sub-category-slug').val(response.slug);

                setTimeout(() => {
                    $('#js-category-dropdown').val(response.category_id).trigger('change');
                }, 1000);

                $('#sub-category-status').val(response.is_active);
                $('#form-submit-btn').text('Update');
                $('#js-add-sub-category-modal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load Sub-Category data', 'error');
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
                    url: "{{ route('sub-categories.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        $('#js-sub-categories-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete sub-category', 'error');
                    }
                });
            }
        });
    });

    // Reset modal when closed
    $('#js-add-sub-category-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add Sub-Category');
        $('#form-submit-btn').text('Add');
        $('#sub-category-form')[0].reset();
        $('#js-sub-category-id').val('');
    });

    // Auto-generate slug when name changes
    $('#sub-category-name').on('input', function() {
        const name = $(this).val();
        $('#sub-category-slug').val(name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });

    $('#sub-category-form').validate({
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
// 03117407286

function getAllCategories(){
        $.ajax({
            url: "{{ route('dropdown.categories') }}",
            type: "GET",
            beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
            success: function (response) {
                var categories = response.categories;
                var categorySelect = $('#js-category-dropdown');
                categorySelect.empty();
                categorySelect.append('<option value="" selected disabled>Select Category</option>');
                $.each(categories, function (index, category) {
                    categorySelect.append('<option value="' + category.id + '">' + category.name + '</option>');
                });
                // categorySelect.selectpicker('refresh');
                return true;
            },
        error: function (xhr) {
            closeAlert();
            showErrorAlert("Something went wrong. Please try again.");
                console.log(xhr.responseText);
            }
        });
    };

</script>
@endsection

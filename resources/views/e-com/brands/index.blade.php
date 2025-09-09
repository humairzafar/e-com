@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" id="abc" >
                <h3 class="card-title mb-0">Brands</h3>
                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-brand-modal">Add Brand</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">

                            <div class="card-body">
                                <table id="js-brand-datatables" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>Name</th>
                                             <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <tbody id="js-brand-datatables-body">
                                            @include('e-com.brands.data-table')

        </div>
    </div>
    <!--modal starts here-->
<div id="js-add-brand-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none; z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="brand-form">
                    @csrf
                    <input type="hidden" name="id" id="js-brand-id">
                    <div class="mb-3">
                        <label for="brand-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="brand-name" placeholder="Enter brand name">
                    </div>
                    <div class="mb-3">
                        <label for="brand-slug" class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" id="brand-slug" placeholder="brand slug" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="brand-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="brand-status">
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
@endsection
@section('styles')
<style>
.modal {
    z-index: 1055 !important;
}
.modal-backdrop {
    z-index: 1050 !important;
}
.modal-dialog-centered {
    display: flex !important;
    align-items: center !important;
    min-height: calc(100% - 1rem) !important;
}
@media (min-width: 576px) {
    .modal-dialog-centered {
        min-height: calc(100% - 3.5rem) !important;
    }
}
</style>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable (only once)
        $('#js-brand-datatables').DataTable({
            responsive: true
        });

        // Handle form submission (Add / Update)
        $('#brand-form').on('submit', function(e) {
            e.preventDefault();

            // Validate form
            if (!$('#brand-form').valid()) {
                return false;
            }

            const formData = $(this).serialize();
            const isUpdate = $('#js-brand-id').val() !== '';
            const url = isUpdate ? "{{ route('brands.update') }}" : "{{ route('brands.store') }}";

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#form-submit-btn').prop('disabled', true);
                },
                success: function(response) {
                    console.log(response);

                    // ✅ Correct way for Bootstrap 5
                    var modalEl = document.getElementById('js-add-brand-modal');
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    // Reset form
                    $('#brand-form')[0].reset();
                    $('#js-brand-id').val('');

                    // Reload table body
                    $('#js-brand-datatables-body').html(response.html);

                    Swal.fire('Success', response.message, 'success');
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                },
                complete: function() {
                    $('#form-submit-btn').prop('disabled', false);
                }
            });
        });
        // Edit brand
        $(document).on('click', '.edit-item-btn', function(e) {
            alert('hello') ;
            console.log('edit button clicked');
            e.preventDefault();

            const recordId = $(this).data('record-id');
            $.ajax({
                url: "{{ route('brands.edit') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: recordId
                },
                success: function(response) {
                    $('#modal-title').text('Edit Brand');
                    $('#js-brand-id').val(response.id);
                    $('#brand-name').val(response.name);
                    $('#brand-slug').val(response.slug);
                    $('#brand-status').val(response.is_active ? '1' : '0');
                    $('#form-submit-btn').text('Update');
                    var modal = new bootstrap.Modal(document.getElementById('js-add-brand-modal'));
                    modal.show();
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            });
        });
        // Delete brand
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
                        url: "{{ route('brands.destroy') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: recordId
                        },
                        success: function(response) {
                            $('#js-brand-datatables-body').html(response.html);
                            Swal.fire('Deleted!', response.message, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                        }
                    });
                }
            });


        // Auto-generate slug from name
        $('#brand-name').on('input', function() {
            const name = $(this).val();
            $('#brand-slug').val(
                name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')
            );
        });
    });
});

    // Reset modal on close
    $('#js-add-brand-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add Brand');
        $('#form-submit-btn').text('Add');
        $('#brand-form')[0].reset();
        $('#js-brand-id').val('');

        // Reset validation state if validator exists
        if ($.fn.validate) {
            const validator = $('#brand-form').validate();
            validator.resetForm();
        }

        $('#brand-form').find('.is-invalid').removeClass('is-invalid');
    });
</script>
@endsection

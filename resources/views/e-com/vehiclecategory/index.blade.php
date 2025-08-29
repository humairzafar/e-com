@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" id="abc" >
                <h3 class="card-title mb-0">vehiclecategorys</h3>
                    @can('add-vehiclecategory')
                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-vehiclecategory-modal">Add vehiclecategory</button>

                    @endcan
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">

                            <div class="card-body">
                                <table id="js-vehiclecategorys-datatables" class="table nowrap align-middle" style="width:100%">
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
                                        <tbody id="js-vehiclecategorys-datatables-body">
                                            @include('e-com.vehiclecategory.data-table')
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
<div id="js-add-vehiclecategory-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none; z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add vehiclecategory</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="vehiclecategory-form">
                    @csrf
                    <input type="hidden" name="id" id="js-vehiclecategory-id">
                    <div class="mb-3">
                        <label for="vehiclecategory-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="vehiclecategory-name" placeholder="Enter vehiclecategory name">
                    </div>
                    <div class="mb-3">
                        <label for="vehiclecategory-slug" class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" id="vehiclecategory-slug" placeholder="vehiclecategory slug" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="vehiclecategory-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="vehiclecategory-status">
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
 #js-add-vehiclecategory-modal
    {
        position: absolute !important;
        top: 0px;
    }
#js-add-employee-modal
{
    position: absolute;
    bottom: 300px;
}
th
{
 text-align: left;
}
@media (min-width: 576px) {
    .modal-dialog-centered {
        min-height: calc(100% - 3.5rem) !important;
    }
}
</style>
@section('scripts')
<script>
  $(document).ready(function() {
    // Initialize DataTable
    $('#js-vehiclecategorys-datatables').DataTable();

    // Handle form submission for both add starts here
    $('#vehiclecategory-form').on('submit', function(e) {
        e.preventDefault();

        // Check if the form is valid using jQuery validator
        if (!$('#vehiclecategory-form').valid()) {
            return false;
        }

        const formData = $(this).serialize();
        const isUpdate = $('#js-vehiclecategory-id').val() !== '';
        const url = isUpdate ? "{{ route('VehiclesCategory.update') }}" : "{{ route('VehiclesCategory.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {
                $('#js-add-vehiclecategory-modal').modal('hide');
                $('#vehiclecategory-form')[0].reset();
                $('#js-vehiclecategory-id').val('');
                $('#js-vehiclecategorys-datatables-body').html(response.html);
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
    $(document).on('click', '.edit-item-btn', function(e) {
    e.preventDefault();
    const recordId = $(this).data('record-id');

    $.ajax({
        url: "{{ route('VehiclesCategory.edit') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            id: recordId
        },
        success: function(response) {
            $('#modal-title').text('Edit Vehicles Category');
            $('#js-vehiclecategory-id').val(response.id); // ✅ fixed ID
            $('#vehiclecategory-name').val(response.name);
            $('#vehiclecategory-slug').val(response.slug);
            $('#vehiclecategory-status').val(response.is_active);
            $('#form-submit-btn').text('Update');
            $('#js-add-vehiclecategory-modal').modal('show');
        },
        error: function(xhr) {
            Swal.fire('Error', 'Failed to load VehiclesCategory data', 'error');
        }
    });
});
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
                    url: "{{ route('VehiclesCategory.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        $('#js-vehiclecategorys-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete vehiclecategorys', 'error');
                    }
                });
            }
        });
    });

    // Reset modal when closed
    $('#js-add-vehiclecategory-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add location');
        $('#form-submit-btn').text('Add');
        $('#vehiclecategory-form')[0].reset();
        $('#js-vehiclecategory-id').val('');
        // 3. Reset validation state
        const validator = $('#vehiclecategory-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#vehiclecategory-form').find('.is-invalid').removeClass('is-invalid');

    });
    $('#vehiclecategory-name').on('input', function() {
        const name = $(this).val();
        $('#vehiclecategory-slug').val(name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });
  });
  </script>
@endsection

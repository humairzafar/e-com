@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" id="abc" >
                <h3 class="card-title mb-0">locations</h3>
                    {{-- @can('add-location') --}}
                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-location-modal">Add location</button>
                    {{-- @endcan --}}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">

                            <div class="card-body">
                                <table id="js-locations-datatables" class="table nowrap align-middle" style="width:100%">
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
                                        <tbody id="js-locations-datatables-body">
                                            @include('e-com.location.data-table')
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
<div id="js-add-location-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none; z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add location</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="location-form">
                    @csrf
                    <input type="hidden" name="id" id="js-location-id">
                    <div class="mb-3">
                        <label for="location-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="location-name" placeholder="Enter location name">
                    </div>
                    <div class="mb-3">
                        <label for="location-slug" class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" id="location-slug" placeholder="location slug" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="location-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="location-status">
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
 #js-add-location-modal
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
    $('#js-locations-datatables').DataTable();

    // Handle form submission for both add starts here
    $('#location-form').on('submit', function(e) {
        e.preventDefault();

        // Check if the form is valid using jQuery validator
        if (!$('#location-form').valid()) {
            return false;
        }

        const formData = $(this).serialize();
        const isUpdate = $('#js-location-id').val() !== '';
        const url = isUpdate ? "{{ route('locations.update') }}" : "{{ route('locations.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {
                $('#js-add-location-modal').modal('hide');
                $('#location-form')[0].reset();
                $('#js-location-id').val('');
                $('#js-locations-datatables-body').html(response.html);
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

    //  edit record date

    $(document).on('click', '.edit-item-btn', function(e) {
        e.preventDefault();
        const recordId = $(this).data('record-id');

        $.ajax({
            url: "{{ route('locations.edit') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: recordId
            },
            success: function(response) {

                $('#modal-title').text('Edit location');
                $('#js-location-id').val(response.id);
                $('#location-name').val(response.name);
                $('#location-slug').val(response.slug);
                $('#location-status').val(response.is_active);
                $('#form-submit-btn').text('Update');
                $('#js-add-location-modal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load location data', 'error');
            }
        });
    });

    // delete record

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
                    url: "{{ route('locations.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        $('#js-locations-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete location', 'error');
                    }
                });
            }
        });
    });

    // Reset modal when closed
    $('#js-add-location-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add location');
        $('#form-submit-btn').text('Add');
        $('#location-form')[0].reset();
        $('#js-location-id').val('');
        // 3. Reset validation state
        const validator = $('#location-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#location-form').find('.is-invalid').removeClass('is-invalid');

    });

     $('#location-name').on('input', function() {
        const name = $(this).val();
        $('#location-slug').val(name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });
});
  </script>
@endsection

@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" id="abc" >
                <h3 class="card-title mb-0">vehicles</h3>
                    @can('add-vehicle')
                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-vehicle-modal">Add vehicle</button>
                    @endcan
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">

                            <div class="card-body">
                                <table id="js-vehicles-datatables" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>Vehicle Id</th>
                                            <th>Category Name</th>
                                            <th>Town</th>
                                            <th>Condition</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <tbody id="js-vehicles-datatables-body">
                                            @include('e-com.vehicle.data-table')
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
<div id="js-add-vehicle-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none; z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add vehicle</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="vehicle-form">
                    @csrf
                    <input type="hidden" name="id" id="js-vehicle-id">
                    <div class="mb-3">
                        <label for="vehicle-id" class="form-label">Vehicle id</label>
                        <input type="number" name="vehicle_id" class="form-control" id="vehicle_id" placeholder="Enter vehicle Id">
                    </div>
                    <div class="mb-3">
                        <label for="vehiclecategory_id" class="form-label">Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="" disabled selected>Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name}}</option>
                        @endforeach
                        </select>
                    </div>
                     <div class="mb-3">
                        <label for="town_id" class="form-label">Town</label>
                        <select class="form-control" name="town_id" id="town_id">
                            <option value="" disabled selected>Select Town</option>
                        @foreach ($towns as $town)
                            <option value="{{ $town->id }}">{{ $town->name}}</option>
                        @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="condition" class="form-label">Condition</label>
                        <select class="form-control" name="condition" id="condition">
                            <option value="" disabled selected>Select Condition</option>
                            <option value="1">New</option>
                            <option value="0">Old</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" name="status" id="vehicle-status">
                            <option value="" disabled selected>Select Condition</option>
                            <option value="1">New</option>
                            <option value="0">Old</option>
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
 #js-add-vehicle-modal
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
    $('#js-vehicles-datatables').DataTable();

    // Handle form submission for both add starts here
    $('#vehicle-form').on('submit', function(e) {
        e.preventDefault();

        // Check if the form is valid using jQuery validator
        if (!$('#vehicle-form').valid()) {
            return false;
        }

        const formData = $(this).serialize();
        const isUpdate = $('#js-vehicle-id').val() !== '';
        const url = isUpdate ? "{{ route('vehicle.update') }}" : "{{ route('vehicle.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {
                $('#js-add-vehicle-modal').modal('hide');
                $('#vehicle-form')[0].reset();
                $('#js-vehicle-id').val('');
                $('#js-vehicles-datatables-body').html(response.html);
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
    $('#js-add-vehicle-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add vehicle');
        $('#form-submit-btn').text('Add');
        $('#vehicle-form')[0].reset();
        $('#js-vehicle-id').val('');
        // 3. Reset validation state
        const validator = $('#vehicle-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#vehicle-form').find('.is-invalid').removeClass('is-invalid');

    });

     $('#vehicle-name').on('input', function() {
        const name = $(this).val();
        $('#vehicle-slug').val(name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });
});
$(document).on('click', '.edit-item-btn', function(e) {
        e.preventDefault();
        const recordId = $(this).data('record-id');

        $.ajax({
            url: "{{ route('vehicle.edit') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: recordId
            },
            success: function(response) {

                $('#modal-title').text('Edit vehicle');
                $('#js-vehicle-id').val(response.id);
                $('#vehicle_id').val(response.vehicle_id);
                $('#vehicle_category').val(response.town);
                $('#vehicle_town').val(response.category);
                $('#condition').val(response.condition);
                $('#vehicle-status').val(response.status);
                $('#form-submit-btn').text('Update');
                $('#js-add-vehicle-modal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load vehicle data', 'error');
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
                    url: "{{ route('vehicle.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        $('#js-vehicles-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete vehicle', 'error');
                    }
                });
            }
        });


    // Reset modal when closed
    $('#js-add-vehicle-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add vehicle');
        $('#form-submit-btn').text('Add');
        $('#vehicle-form')[0].reset();
        $('#js-vehicle-id').val('');
        // 3. Reset validation state
        const validator = $('#vehicle-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#vehicle-form').find('.is-invalid').removeClass('is-invalid');

    });
     });
</script>
@endsection


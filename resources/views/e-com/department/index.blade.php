@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" id="abc" >
                <h3 class="card-title mb-0">Departments</h3>
                    @can('add-department')
                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-department-modal">Add Department</button>
                    @endcan
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">

                            <div class="card-body">
                                <table id="js-department-datatables" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Is Head of </th>
                                             <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <tbody id="js-department-datatables-body">
                                            @include('e-com.department.data-table')

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
<div id="js-add-department-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none; z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add Department</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="department-form">
                    @csrf
                    <input type="hidden" name="id" id="js-department-id">
                    <div class="mb-3">
                        <label for="department-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="department-name" placeholder="Enter Department name">
                    </div>
                    <div class="mb-3">
                        <label for="department-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="department-status">
                            <option value="" disabled selected>Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="head-department-status" class="form-label">Is Head Office</label>
                        <select class="form-control" name="is_head_office_department" id="head-department-status">
                            <option value="" disabled selected>Select Status</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
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
#js-add-department-modal
{
    position: absolute;
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
    // Initialize DataTable
    $('#js-department-datatables').DataTable();

    // Handle form submission for both add starts here
    $('#department-form').on('submit', function(e) {
        e.preventDefault();

        // Check if the form is valid using jQuery validator
        if (!$('#department-form').valid()) {
            return false;
        }

        const formData = $(this).serialize();
        const isUpdate = $('#js-department-id').val() !== '';
        const url = isUpdate ? "{{ route('department.update') }}" : "{{ route('department.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {

                console.log(response)
                $('#js-add-department-modal').modal('hide');
                $('#department-form')[0].reset();
                $('#js-department-id').val('');

                $('#js-department-datatables-body').html(response.html);
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
    // Edit department
    $(document).on('click', '.edit-item-btn', function(e) {
        e.preventDefault();
        const recordId = $(this).data('record-id');
       console.log(recordId);

        $.ajax({
            url: "{{ route('department.edit') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: recordId
            },
            success: function(response) {

                $('#modal-title').text('Edit Department');
                $('#js-department-id').val(response.id);
                $('#department-name').val(response.name);
                $('#department-status').val(response.is_active);
                $('#head-department-status').val(response.is_head_office_department);
                $('#form-submit-btn').text('Update');
                $('#js-add-department-modal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load department data', 'error');
            }
        });
    });

    // Delete deaprtment
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
                    url: "{{ route('department.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        if(response.success)
                        {
                            $('#js-department-datatables-body').html();
                            $('#js-department-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                        }

                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete department', 'error');
                    }
                });
            }
        });
    });

    // Reset modal when closed
    $('#js-add-department-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add department');
        $('#form-submit-btn').text('Add');
        $('#department-form')[0].reset();
        $('#js-department-id').val('');
        // 3. Reset validation state
        const validator = $('#department-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#department-form').find('.is-invalid').removeClass('is-invalid');

    });

    // Auto-generate slug when name changes



});

</script>
@endsection






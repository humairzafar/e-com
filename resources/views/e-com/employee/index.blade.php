@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" id="abc" >
                <h3 class="card-title mb-0">Employee</h3>

                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-employee-modal">Add Employee</button>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                            <div class="card-body">
                                <table id="js-employee-datatables" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col" style="width: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>FirstName</th>
                                            <th>LastName</th>
                                            <th>CNIC</th>
                                            <th>DOB</th>
                                            <th>DOJ</th>
                                            <th>Department</th>
                                            <th>Designation</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <tbody id="js-employee-datatables-body">
                                            @include('e-com.employee.data-table')

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
<div id="js-add-employee-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none; z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add designation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" id="employee-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="js-employee-id">
                    <div class="mb-3">
                        <label for="employee-fname" class="form-label">FirstName</label>
                        <input type="text" name="firstname" class="form-control" id="employee-fname" placeholder="Enter First name">
                    </div>
                     <div class="mb-3">
                        <label for="employee-lname" class="form-label">LastName</label>
                        <input type="text" name="lastname" class="form-control" id="employee-lname" placeholder="Enter Last name">
                    </div>
                    <div class="mb-3">
                        <label for="employee-cnic" class="form-label">CNIC</label>
                        <input type="number" maxlength="13" minlength="13" name="cnic" class="form-control" id="employee-cnic" placeholder="Enter cnic">
                    </div>
                    <div class="mb-3">
                        <label for="employee-dob" class="form-label">DOB</label>
                        <input type="date" name="dob" class="form-control" id="employee-dob" placeholder="Enter Last name">
                    </div>
                    <div class="mb-3">
                        <label for="employee-doj" class="form-label">DOJ</label>
                        <input type="date" name="doj" class="form-control" id="employee-doj" placeholder="Enter Last name">
                    </div>
                    <div class="mb-3">
                        <label for="employee-dep" class="form-label">Department</label>
                        <select class="form-control" name="department_id" id="department_id">
                            <option>Select department</option>
                            @foreach ($departments as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="employee-designation" class="form-label">Designation</label>
                        <select class="form-control" name="designation_id" id="designation_id">
                            <option>Select Designation</option>
                            @foreach ($designations as $degs)
                                <option value="{{ $degs->id }}">{{ $degs->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="employee-image" class="form-label">Select Image</label>
                        <input type="file" name="image" class="form-control" id="employee_image">
                    </div>
                    <div class="mb-3">
                        <label for="employee-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="employee-status">
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
    $('#js-employee-datatables').DataTable();

    // Handle form submission for both add starts here
    $('#employee-form').on('submit', function(e) {
        e.preventDefault();

        // Check if the form is valid using jQuery validator
        if (!$('#employee-form').valid()) {
            return false;
        }

        // const formData = $(this).serialize();

        const form = $('#employee-form')[0];
        const formData = new FormData(form);

        console.log(formData);
        const isUpdate = $('#js-employee-id').val() !== '';
        const url = isUpdate ? "{{ route('employee.update') }}" : "{{ route('employee.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {
                console.log(response)
                $('#js-add-employee-modal').modal('hide');
                $('#employee-form')[0].reset();
                $('#js-employee-id').val('');
                $('#js-employee-datatables-body').html(response.html);
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
            url: "{{ route('employee.edit') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: recordId
            },
            success: function(response) {
                $('#modal-title').text('Edit designation');
                $('#js-employee-id').val(response.id);
                $('#employee-fname').val(response.firstname);
                $('#employee-lname').val(response.lastname);
                $('#employee-cnic').val(response.cnic);
                $('#employee-dob').val(response.dob);
                $('#employee-doj').val(response.doj);
                $('#department_id').val(response.department_id);
                $('#designation_id').val(response.designation_id);
                $('#employee-image').val(response.image);
                $('#employee-status').val(response.is_active);
                $('#form-submit-btn').text('Update Employee Data');
                $('#js-add-employee-modal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load designation data', 'error');
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
                    url: "{{ route('employee.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        if(response.success)
                        {
                            $('#js-employee-datatables-body').html();
                            $('#js-employee-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                        }

                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete designation', 'error');
                    }
                });
            }
        });
    });
    $('#js-add-employee-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add Employee');
        $('#form-submit-btn').text('Add');
        $('#employee-form')[0].reset();
        $('#js-employee-id').val('');
        // 3. Reset validation state
        const validator = $('#employee-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#employee-form').find('.is-invalid').removeClass('is-invalid');

    });
});
</script>
@endsection

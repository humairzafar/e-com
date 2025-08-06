@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" id="abc" >
                <h3 class="card-title mb-0">Designation</h3>

                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-designation-modal">Add designation</button>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                            <div class="card-body">
                                <table id="js-designation-datatables" class="table nowrap align-middle" style="width:100%">
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
                                        <tbody id="js-designation-datatables-body">
                                            @include('e-com.designation.data-table')

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
<div id="js-add-designation-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none; z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add designation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="designation-form">
                    @csrf
                    <input type="hidden" name="id" id="js-designation-id">
                    <div class="mb-3">
                        <label for="designation-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="designation-name" placeholder="Enter designation name">
                    </div>
                    <div class="mb-3">
                        <label for="designation-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="designation-status">
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
#js-add-designation-modal
{
    position: absolute;
    top: 0px;
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
    $('#js-designation-datatables').DataTable();

    // Handle form submission for both add starts here
    $('#designation-form').on('submit', function(e) {
        e.preventDefault();
        // Check if the form is valid using jQuery validator
        // if (!$('#designation-form').valid()) {
        //     return false;
        // }

        const formData = $(this).serialize();
        const isUpdate = $('#js-designation-id').val() !== '';
        const url = isUpdate ? "{{ route('designation.update') }}" : "{{ route('designation.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {

                console.log(response)
                $('#js-add-designation-modal').modal('hide');
                $('#designation-form')[0].reset();
                $('#designation-id').val('');
                $('#js-designation-datatables-body').html(response.html);
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
    $('#js-add-designation-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add designation');
        $('#form-submit-btn').text('Add');
        $('#designation-form')[0].reset();
        $('#js-designation-id').val('');
        // 3. Reset validation state
        const validator = $('#designation-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#designation-form').find('.is-invalid').removeClass('is-invalid');

    });
    $(document).on('click', '.edit-item-btn', function(e) {
        e.preventDefault();
        const recordId = $(this).data('record-id');
       console.log(recordId);

        $.ajax({
            url: "{{ route('designation.edit') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: recordId
            },
            success: function(response) {
                $('#modal-title').text('Edit designation');
                $('#js-designation-id').val(response.id);
                $('#designation-name').val(response.name);
                $('#designation-status').val(response.is_active);
                $('#form-submit-btn').text('Update');
                $('#js-add-designation-modal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load designation data', 'error');
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
                    url: "{{ route('designation.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        if(response.success)
                        {
                            $('#js-designation-datatables-body').html();
                            $('#js-designation-datatables-body').html(response.html);
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

    $('#js-add-designation-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add designation');
        $('#form-submit-btn').text('Add');
        $('#designation-form')[0].reset();
        $('#js-designation-id').val('');
        // 3. Reset validation state
        const validator = $('#designation-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#designation-form').find('.is-invalid').removeClass('is-invalid');

    });
});
</script>
@endsection

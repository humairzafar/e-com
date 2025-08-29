@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Roles And Permissions</h3>
                @can('add-roles')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    Add Role
                </button>
                @endcan
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="js-roles-datatables" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Role Name</th>
                                            <th>Permissions</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-roles-datatables-body">
                                        @include('e-com.roles-permissions.data-table', ['roles' => $roles])
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
<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" id="role-form">
      @csrf
      <input type="hidden" name="id" id="js-role-id"> <!-- hidden for edit -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" id="role-name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Assign Permissions</label><br>
            @foreach($permissions as $permission)
              <div class="form-check form-check-inline">
                <input class="form-check-input role-permission" id="role-permissions" type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                <label class="form-check-label">{{ $permission->name }}</label>
              </div>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" id="form-submit-btn">Save Role</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- end role modal --}}
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    // Initialize DataTable
    $('#js-roles-datatables').DataTable();

    // Handle form submission for add/update
    $('#role-form').on('submit', function(e) {
        e.preventDefault();

        if (!$('#role-form').valid()) {
            return false;
        }

        const formData = $(this).serialize();
        const isUpdate = $('#js-role-id').val() !== '';
        const url = isUpdate ? "{{ route('roles.update') }}" : "{{ route('roles.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function() {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {
                $('#addRoleModal').modal('hide');
                $('#role-form')[0].reset();
                $('#js-role-id').val('');
                $('#js-roles-datatables-body').html(response.html);
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

    // Edit role
    $(document).on('click', '.edit-item-btn', function(e) {
        e.preventDefault();
        const recordId = $(this).data('record-id');
        const url = "{{ route('roles.edit', ':id') }}".replace(':id', recordId);

        $.ajax({
            url: url,
            method: 'GET',
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                $('#addRoleModalLabel').text('Edit Role');
                $('#js-role-id').val(response.id);
                $('#role-name').val(response.name);

                // Uncheck all permissions first
                $('.role-permission').prop('checked', false);

                // Check the permissions that belong to the role
                if (response.permissions && response.permissions.length > 0) {
                    response.permissions.forEach(function(permission) {
                        $('.role-permission[value="' + permission.id + '"]').prop('checked', true);
                    });
                }

                $('#form-submit-btn').text('Update');
                $('#addRoleModal').modal('show');
            },
            error: function() {
                Swal.fire('Error', 'Failed to load role data', 'error');
            }
        });
    });

    // Delete role
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
                    url: "{{ route('roles.destroy') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}", id: recordId },
                    success: function(response) {
                        $('#js-roles-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to delete role', 'error');
                    }
                });
            }
        });
    });

    // Reset modal on close
    $('#addRoleModal').on('hidden.bs.modal', function() {
        $('#addRoleModalLabel').text('Add Role');
        $('#form-submit-btn').text('Save Role');
        $('#role-form')[0].reset();
        $('#js-role-id').val('');

        const validator = $('#role-form').validate();
        validator.resetForm();
        $('#role-form').find('.is-invalid').removeClass('is-invalid');
    });
  });
</script>

@endsection

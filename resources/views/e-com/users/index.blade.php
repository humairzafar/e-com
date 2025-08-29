@extends('layout.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Users</h3>
                @can('add-user')
                <button type="button" id="addUserBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-user-modal">Add User</button>
                 @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="js-users-datatables" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Roles</th>
                                            <th>Permissions</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-users-datatables-body">
                                        @include('e-com.users.data-table', ['users' => $users])
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
    <form action="{{ route('roles.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Assign Permissions</label><br>
            @foreach($permissions as $permission)
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                <label class="form-check-label">{{ $permission->name }}</label>
              </div>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Role</button>
        </div>
      </div>
    </form>
  </div>
</div>
{{-- end role modal --}}

<!-- Add/Edit User Modal -->
<div id="js-add-user-modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="user-form" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="js-user-id">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="user-name">Name</label>
                        <input type="text" name="name" id="user-name" class="form-control">
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="user-email">Email</label>
                        <input type="email" name="email" id="user-email" class="form-control">
                    </div>

                      <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                            {{-- <small class="text-muted">Leave blank to keep current password (edit mode)</small> --}}
                        </div>
                         <div class="mb-3">
                        <label for="part-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="part-status">
                            <option value="" disabled selected>Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Role (single select) -->
                    <div class="mb-3">
                        <label for="user-role">Role</label>
                        <select name="role_id" id="user-role" class="form-select">
                            <option value="">-- Select Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                <button type="submit" class="btn btn-primary" id="form-submit-btn">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- end add and edit modal --}}
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    // Initialize DataTable
    $('#js-users-datatables').DataTable();

    // Handle form submission for both add starts here
    $('#user-form').on('submit', function(e) {
        e.preventDefault();

        // Check if the form is valid using jQuery validator
        if (!$('#user-form').valid()) {
            return false;
        }

        const formData = $(this).serialize();
        const isUpdate = $('#js-user-id').val() !== '';
        const url = isUpdate ? "{{ route('users.update') }}" : "{{ route('users.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                $('#form-submit-btn').prop('disabled', true);
            },
            success: function(response) {
                $('#js-add-user-modal').modal('hide');
                $('#user-form')[0].reset();
                $('#js-user-id').val('');
                $('#js-users-datatables-body').html(response.html);
                Swal.fire('Success', response.message, 'success');
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong', 'error');
            },
            complete: function() {
                $('#user-submit-btn').prop('disabled', false);
            }
        });
    });
    $('#js-add-user-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add User');
        $('#form-submit-btn').text('Add');
        $('#user-form')[0].reset();
        $('#js-user-id').val('');
        // 3. Reset validation state
        const validator = $('#user-form').validate();
        validator.resetForm();

        // 4. Remove error classes from inputs
        $('#user-form').find('.is-invalid').removeClass('is-invalid');

    });
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
    });

</script>
@endsection



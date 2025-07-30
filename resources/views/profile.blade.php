@extends('layout.app');
@section('content')
    <div class="card mt-xxl-n5">
        <div class="card-header">
            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                        <i class="fas fa-home"></i> Personal Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                        <i class="far fa-user"></i> Change Password
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content">
                <div class="tab-pane active" id="personalDetails" role="tabpanel">
                    <form method="post" id="js-profile-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="firstnameInput" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="name" id="firstnameInput" placeholder="Enter your firstname" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="emailInput" class="form-label">Email Address</label>
                                    <input type="email" class="form-control"  name="email" id="emailInput" placeholder="Enter your email" value="{{ $user->email }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="phonenumberInput" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number" id="phonenumberInput" placeholder="Enter your phone number" value="{{ $user->phone_number }}">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="JoiningdatInput" class="form-label">Joining Date</label>
                                    <input type="date" class="form-control" name="joining_date" data-provider="flatpickr" id="JoiningdatInput" data-date-format="d M, Y"  value="{{ $user->joining_date ? date('Y-m-d', strtotime($user->joining_date)) : '' }}" placeholder="Select date" />
                                </div>
                            </div>
                            <!--end col-->


                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cityInput" class="form-label">City</label>
                                    <input type="text" class="form-control" id="cityInput" placeholder="City" value="California" />
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="countryInput" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="countryInput" placeholder="Country" value="United States" />
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="zipcodeInput" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" minlength="5" maxlength="6" id="zipcodeInput" placeholder="Enter zipcode" value="90011">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="mb-3 pb-2">
                                    <label for="exampleFormControlTextarea" class="form-label">BioGraphy</label>
                                    <textarea class="form-control" name="bio" id="exampleFormControlTextarea" placeholder="Enter your description" rows="3">{{ $user->bio }}</textarea>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="js-update-profile">Updates</button>
                                    <button type="button" class="btn btn-soft-success">Cancel</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <!--end tab-pane-->
                <div class="tab-pane" id="changePassword" role="tabpanel">
                    <form method="post" id="js-change-password-form">
                        @csrf
                        <div class="row g-2">

                            <!--end col-->
                            <div class="col-lg-4">
                                <div>
                                    <label for="newpasswordInput" class="form-label">New Password*</label>
                                    <input type="password" name="password" class="form-control" id="newpasswordInput" placeholder="Enter new password">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div>
                                    <label for="confirmpasswordInput" class="form-label">Confirm Password*</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="confirmpasswordInput" placeholder="Confirm password">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" id="js-change-password" class="btn btn-success">Change Password</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                    <div class="mt-4 mb-3 border-bottom pb-2">
                        <div class="float-end">
                            <a href="javascript:void(0);" class="link-primary">All Logout</a>
                        </div>
                        <h5 class="card-title">Login History</h5>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light text-primary rounded-3 fs-18 material-shadow">
                                <i class="ri-smartphone-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>iPhone 12 Pro</h6>
                            <p class="text-muted mb-0">Los Angeles, United States - March 16 at 2:47PM</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);">Logout</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light text-primary rounded-3 fs-18 material-shadow">
                                <i class="ri-tablet-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>Apple iPad Pro</h6>
                            <p class="text-muted mb-0">Washington, United States - November 06 at 10:43AM</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);">Logout</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light text-primary rounded-3 fs-18 material-shadow">
                                <i class="ri-smartphone-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>Galaxy S21 Ultra 5G</h6>
                            <p class="text-muted mb-0">Conneticut, United States - June 12 at 3:24PM</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);">Logout</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light text-primary rounded-3 fs-18 material-shadow">
                                <i class="ri-macbook-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>Dell Inspiron 14</h6>
                            <p class="text-muted mb-0">Phoenix, United States - July 26 at 8:10AM</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);">Logout</a>
                        </div>
                    </div>
                </div>
                <!--end tab-pane-->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        $('#js-update-profile').on('click', function(){
            event.preventDefault();
            const formData = $('#js-profile-form').serialize();
            $.ajax({
                url: "{{ route('profile.update') }}",
                type: "POST",
                data: formData,
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(response){ 
                    console.log(response);
                    if(response.success){
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success'
                        });
                    }
                },
                error: function(xhr, status, error){
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseText,
                        icon: 'error'
                    });
                }
            })
        })
    });

    $('#js-change-password').on('click', function(){
        event.preventDefault();
        const formData = $('#js-change-password-form').serialize();
        $.ajax({
            url: "{{ route('profile.change-password') }}",
            type: "POST",
            data: formData,
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response){
                if(response.success){
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success'
                    });
                    $('#js-change-password-form')[0].reset();
                }
            },
            error: function(xhr, status, error){
                Swal.fire({
                    title: 'Error',
                    text: xhr.responseText,
                    icon: 'error'
                });
            }
        })
    })
</script>
@endsection

@extends('layout.app');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title mb-0">Products</h3>
                @can('create-product')
                <button type="button"  class="btn btn-primary" id="js-add-product-btn">Add Product</button>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <table id="js-products-datatables" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>name</th>
                                            <th>SKU</th>
                                            <th>Category</th>
                                            <th>Sub-Category</th>
                                            <th>Unit_Cost_Price</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Status</th>
                                            <th style="width: 150px;">Description</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <tbody id="js-products-datatables-body">
                                            @include('e-com.products.data-table', ['products' => $products])
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

<!--modal starts here-->
<div id="js-add-product-modal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0" id="modal-title">Add Product</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="product-form" enctype="multipart/form-data>
                    @csrf
                    <input type="hidden" name="id" id="js-product-id" value="">
                    <div class="mb-3">
                        <label for="product-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="product-name" placeholder="Enter product name">
                    </div>


                    <div class="mb-3">
                        <label for="product-category"  class="form-label">Category</label>
                        <select class="form-control" name="category_id" id="js-category-dropdown" required>
                            <option value="" disabled selected>Select Category</option>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="product-sub-category" class="form-label">Sub-Category</label>
                        <select class="form-control" name="sub_category_id" id="js-sub-category-dropdown" required>
                            <option value="" disabled selected>Select Sub-Category</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product-quantity" class="form-label"> Quantity</label>
                        <input type="text" name="quantity" class="form-control" id="current-quantity" placeholder="Enter product quantity">
                    </div>
                    <div class="mb-3 d-none" id="add-or-subtract-quantity-div">
                        <label for="product-status"  class="form-label">Add or Subtract Quantity</label>
                        <select class="form-control" name="add_or_subtract" id="add-or-subtract-quantity-list">
                            <option value="" selected >Select an Operation</option>
                            <option value="1" >Add</option>
                            <option value="0">Subtract</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none"  id="add-or-subtract-quantity-input">
                        <label for="product-quantity" class="form-label"> Quantity to Add/Subtract</label>
                        <input type="text" name="add_or_subtract_quantity" class="form-control" id="add-or-subtract-quantity" placeholder="Enter product quantity">
                    </div>
                    <div class="mb-3">
                        <label for="product-unit-cost-price" class="form-label">Unit Cost Price</label>
                        <input type="text" name="unit_cost_price" class="form-control" id="product-unit-cost-price" placeholder="Enter product unit cost price">
                    </div>
                    <div class="mb-3">
                        <label for="product-price" class="form-label">Price</label>
                        <input type="text" name="price" class="form-control" id="product-price" placeholder="Enter product price">
                    <div class="mb-3">
                        <label for="product-status" class="form-label">Status</label>
                        <select class="form-control" name="is_active" id="product-status">
                            <option value="" disabled selected>Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product-image" class="form-label">Product Image</label>
                        <input type="file" name="image_url" class="form-control" id="product-image" accept="image/*">
                        <div class="mt-2">
                            <img id="image-preview" src="#" alt="Preview" style="max-height: 200px; display: none;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="product-description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="product-description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
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
<!--modal ends here-->

@endsection


@section('scripts')
<script>
  $(document).ready(function() {
    // Initialize DataTable
    $('#js-products-datatables').DataTable();


    // add button click starts here
    $('#js-add-product-btn').on('click', function() {
         // Make quantity field editable
    $('#current-quantity').prop('readonly', false);
    // Hide quantity adjustment fields (in case shown from edit mode)
    $('#add-or-subtract-quantity-div').addClass('d-none');
    $('#add-or-subtract-quantity-input').addClass('d-none');
        $('#js-add-product-modal').modal('show');
    });
    // add button click ends here
    $('#product-image').change(function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#image-preview').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(file);
    }
});


    // get all categories starts here
    // $('#js-add-product-modal').on('shown.bs.modal', function() {
    //     getAllCategories();
    // });
    $('#js-add-product-modal').on('shown.bs.modal', async function() {
    try {
        await getAllCategories();
    } catch (error) {
        console.error('Error loading categories:', error);
        Swal.fire('Error', 'Failed to load categories', 'error');
    }
});

    // get all categories ends here


    // close modal starts here
    $('#js-add-product-modal').on('hidden.bs.modal', function() {
    // 1. Reset the form (clears inputs)
    $('#product-form')[0].reset();

    // 2. Remove all error messages
    // $('.error').remove();

    $('#form-submit-btn').prop('disabled', false).removeClass('btn-loader');
    // 3. Reset validation state
    const validator = $('#product-form').validate();
    validator.resetForm();

    // 4. Remove error classes from inputs
    $('#product-form').find('.is-invalid').removeClass('is-invalid');

    // 5. Reset any custom fields (like your quantity fields)
    $('#add-or-subtract-quantity-div').addClass('d-none');
    $('#add-or-subtract-quantity-input').addClass('d-none');
    $('#js-product-id').val('');
    $('#modal-title').text('Add Product');
    $('#form-submit-btn').text('Add');
});

    // close modal ends here

    // Handle form submission for both add starts here
    $('#product-form').on('submit', function(e) {
        e.preventDefault();


        // Check if the form is valid using jQuery validator
        if (!$('#product-form').valid()) {
        return false; // Stop if validation fails
    }
      // Only show loader AFTER validation passes
      const submitBtn = $('#form-submit-btn');
        submitBtn.prop('disabled', true);
        submitBtn.addClass('btn-loader');

        // const formData = $(this).serialize();
        // console.log(formData);
        const formData = new FormData(this);

        const isUpdate = $('#js-product-id').val() !== '';
    const url = isUpdate ? "{{ route('products.update') }}" : "{{ route('products.store') }}";
        // console.log(url);


        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false, // Required for file uploads
            contentType: false, // Required for file uploads
            beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
            success: function(response) {
                $('#js-add-product-modal').modal('hide');
                $('#image-preview').attr('src', '#').hide();
                $('#product-form')[0].reset();
                $('#js-product-id').val('');
                $('#js-products-datatables-body').html(response.html);
                Swal.fire('Success', response.message, 'success');
                $('#add-or-subtract-quantity-div').addClass('d-none');
                $('#add-or-subtract-quantity-input').addClass('d-none');
                // Reset image preview if exists
                $('#image-preview').attr('src', '#').hide();
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong', 'error');
            },
            complete: function() {
                $('#form-submit-btn').prop('disabled', false);
                submitBtn.prop('disabled', false);
                submitBtn.removeClass('btn-loader');
            }
        });
    });
    // Handle form submission for both add ends here
    // Edit category
//     $(document).on('click', '.edit-item-btn', function(e) {
//     e.preventDefault();
//     const recordId = $(this).data('record-id');
//     $('#js-product-id').val(recordId);
//     $('#js-add-product-modal').modal('show');
//     $('#modal-title').text('Edit Product');
//     $('#add-or-subtract-quantity-div').removeClass('d-none');
//     $('#add-or-subtract-quantity-input').removeClass('d-none');
//     $('#form-submit-btn').text('Update');
//     $('#current-quantity').val('');
//        // Get the button and add loader
//        const editBtn = $(this);
//     editBtn.addClass('btn-loader').prop('disabled', true);

//     $.ajax({
//         url: "{{ route('products.edit', ['id' => ':id']) }}".replace(':id', recordId),
//         method: 'GET',
//         beforeSend: function(xhr) {
//             xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
//         },
//         success: function(response) {
//             if(response.success) {
//                 console.log('inside the if check', response);
//                 var data = response.data;

//                 // Set form values
//                 $('#product-name').val(data.name);
//                 $('#product-status').val(data.is_active ? 1 : 0);
//                 $('#current-quantity').val(data.quantity).prop('readonly', true);
//                 $('#product-unit-cost-price').val(data.unit_cost_price);
//                 $('#product-price').val(data.price);

//                 // Set image preview
//                 if (data.image_path) {
//                     $('#image-preview').attr('src', "{{ asset('storage') }}/" + data.image_path).show();
//                 } else {
//                     $('#image-preview').hide();
//                 }

//                 // Set description
//                 $('#product-description').val(data.description);

//                 // Set category dropdowns with delays
//                 setTimeout(() => {
//                     $('#js-category-dropdown').val(data.category_id).trigger('change');
//                 }, 600);

//                 setTimeout(() => {
//                     $('#js-sub-category-dropdown').val(data.sub_category_id).trigger('change');
//                 }, 1000);

//             }
//         },
//         error: function(xhr) {
//             Swal.fire('Error', 'Failed to load product data', 'error');
//         },
//         complete: function() { // Always runs
//             editBtn.removeClass('btn-loader').prop('disabled', false);
//         }
//     });
// });
$(document).on('click', '.edit-item-btn', async function(e) {
    e.preventDefault();
    const editBtn = $(this);
    editBtn.addClass('btn-loader').prop('disabled', true);

    try {
        const recordId = editBtn.data('record-id');

        // Fetch product data first
        const response = await $.ajax({
            url: "{{ route('products.edit', ['id' => ':id']) }}".replace(':id', recordId),
            method: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        if (response.success) {
            const data = response.data;

            // Set form values (except dropdowns)
            $('#js-product-id').val(recordId);
            $('#modal-title').text('Edit Product');
            $('#product-name').val(data.name);
            $('#product-status').val(data.is_active ? 1 : 0);
            $('#current-quantity').val(data.quantity).prop('readonly', true);
            $('#product-unit-cost-price').val(data.unit_cost_price);
            $('#product-price').val(data.price);
            $('#product-description').val(data.description);
            $('#add-or-subtract-quantity-div').removeClass('d-none');
            $('#add-or-subtract-quantity-input').removeClass('d-none');
            $('#form-submit-btn').text('Update');

            // Set image preview
            if (data.image_url) {
                $('#image-preview').attr('src',data.image_url).show();
            } else {
                $('#image-preview').hide();
            }

            // Load categories and set values AFTER modal is shown
            $('#js-add-product-modal').one('shown.bs.modal', async function() {
                try {
                    // Load categories first
                    await getAllCategories();

                    // Set category value and trigger change
                    $('#js-category-dropdown').val(data.category_id).trigger('change');

                    // Wait for subcategories to load
                    await getAllSubCategories(data.category_id);

                    // Set subcategory value
                    $('#js-sub-category-dropdown').val(data.sub_category_id);
                } catch (error) {
                    console.error('Error loading dropdowns:', error);
                }
            }).modal('show');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Failed to load product data', 'error');
    } finally {
        editBtn.removeClass('btn-loader').prop('disabled', false);
    }
});
    // Delete category
    $(document).on('click', '.remove-item-btn', function(e) {
        e.preventDefault();
        const recordId = $(this).data('record-id');
        const deleteBtn = $(this);
        deleteBtn.addClass('btn-loader').prop('disabled', true);

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
                    url: "{{ route('products.destroy') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: recordId
                    },
                    success: function(response) {
                        $('#js-products-datatables-body').html(response.html);
                        Swal.fire('Deleted!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete sub-category', 'error');
                    },
                complete: function() {
                    deleteBtn.removeClass('btn-loader').prop('disabled', false);
                }
                });
            }else {
            // Reset if user cancels
            deleteBtn.removeClass('btn-loader').prop('disabled', false);
        }
        });
    });

    // Reset modal when closed
    $('#js-add-sub-category-modal').on('hidden.bs.modal', function() {
        $('#modal-title').text('Add Sub-Category');
        $('#form-submit-btn').text('Add');
        $('#product-form')[0].reset();
        $('#js-sub-category-id').val('');
    });
// 1. Define custom validation method
$.validator.addMethod("priceGreaterThanCost", function(value, element) {
    var unitCostPrice = parseFloat($('[name="unit_cost_price"]').val());
    var price = parseFloat(value);

    // Skip validation if either field is empty (let 'required' handle that)
    if (isNaN(unitCostPrice) || isNaN(price)) {
        return true;
    }


    return price >= unitCostPrice;
}, "Price must be greater than or equal to Unit Cost Price");
$.validator.addMethod("lessThanOriginalQuantity", function(value, element) {
    var selectedOperation = $('#add-or-subtract-quantity-list').val();
    var originalQuantity = parseFloat($('#current-quantity').val());
    var enteredQuantity = parseFloat(value);

    if (selectedOperation !== '0') {
        return true; // Only apply this rule if "Subtract" is selected
    }

    if (isNaN(originalQuantity) || isNaN(enteredQuantity)) {
        return true; // Let 'required' and 'number' handle other validation
    }

    return enteredQuantity <= originalQuantity;
}, "Quantity to subtract cannot exceed current quantity");
$('#product-form').validate({
    rules: {
        name: {
            required: true,
            minlength: 3
        },
        is_active: {
            required: true,
        },
        quantity: {
            required: true,
        },
        unit_cost_price: {
            required: true,
            number: true // Ensure it's a valid number
        },
        price: {
            required: true,
            number: true, // Ensure it's a valid number
            priceGreaterThanCost: true // Custom rule
        },
         // This function determines WHEN the field is required
         add_or_subtract_quantity: {
            required: function() {
                var val = $('#add-or-subtract-quantity-list').val();
                return val === '1' || val === '0'; // Only required if Add or Subtract is selected
            },
            number: true,
            lessThanOriginalQuantity: true // <-- Add this line
        },
        description: {
    required: true,
    minlength: 10,
    maxlength: 500
},
invalidHandler: function() {
        // Reset button if validation fails
        $('#form-submit-btn').prop('disabled', false).removeClass('btn-loader');
    }




    },
    messages: {
        name: {
            required: "Name is required",
            minlength: "Name must be at least 3 characters long"
        },
        is_active: {
            required: "Status is required",
        },
        quantity: {
            required: "Quantity is required"
        },
        unit_cost_price: {
            required: "Unit Cost Price is required",
            number: "Must be a valid number"
        },
        price: {
            required: "Price is required",
            number: "Must be a valid number",
            priceGreaterThanCost: "Price must be ≥ Unit Cost Price" // Custom error message
        },
        add_or_subtract_quantity:{
            required:'Quantity is required to add or subtract'
        },
        description: {
    required: "Description is required",
    minlength: "Description must be at least 10 characters long",
    maxlength: "Description cannot exceed 500 characters"
},


    }
});
});
// 03117407286

// function getAllCategories(){
//         $.ajax({
//             url: "{{ route('dropdown.categories') }}",
//             type: "GET",
//             beforeSend: function(xhr){
//                     xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
//                 },
//             success: function (response) {
//                 var categories = response.categories;
//                 var categorySelect = $('#js-category-dropdown');
//                 categorySelect.empty();
//                 categorySelect.append('<option value="" selected disabled>Select Category</option>');
//                 $.each(categories, function (index, category) {
//                     categorySelect.append('<option value="' + category.id + '">' + category.name + '</option>');
//                 });
//                 // categorySelect.selectpicker('refresh');
//                 return true;
//             },
//         error: function (xhr) {
//             closeAlert();
//             showErrorAlert("Something went wrong. Please try again.");
//                 console.log(xhr.responseText);
//             }
//         });
//     };
$('#js-category-dropdown').on('change', async function() {
    const categoryId = $(this).val();
    if (categoryId) {
        try {
            await getAllSubCategories(categoryId);
        } catch (error) {
            console.error('Error loading subcategories:', error);
            Swal.fire('Error', 'Failed to load subcategories', 'error');
        }
    }
});
//     $('#js-category-dropdown').on('change', function() {

//         getAllSubCategories($('#js-category-dropdown').val());
//     });
//     // get all sub categories starts here
// function getAllSubCategories(categoryId = null){
//     $.ajax({
//         url: "{{ route('dropdown.sub-categories') }}",
//         type: "GET",
//         data: {
//             category_id: categoryId
//         },
//         beforeSend: function(xhr){
//             xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
//         },
//         success: function (response) {
//             console.log(response);
//             var subCategories = response.subCategories;
//             var subCategorySelect = $('#js-sub-category-dropdown');
//             subCategorySelect.empty();
//             subCategorySelect.append('<option value="" selected disabled>Select Sub-Category</option>');
//             $.each(subCategories, function (index, subCategory) {
//                 subCategorySelect.append('<option value="' + subCategory.id + '">' + subCategory.name + '</option>');
//             });
//             return true;
//         },
//         error: function (xhr) {
//             closeAlert();
//             showErrorAlert("Something went wrong. Please try again.");
//             console.log(xhr.responseText);
//         }
//     });
// }
function getAllCategories() {
    const dropdown = $('#js-category-dropdown');
    dropdown.empty().append('<option value="" disabled selected>Loading categories...</option>');
    dropdown.prop('disabled', true);
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "{{ route('dropdown.categories') }}",
            type: "GET",
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {
                var categories = response.categories;
                var categorySelect = $('#js-category-dropdown');
                categorySelect.empty();
                categorySelect.append('<option value="" selected disabled>Select Category</option>');
                $.each(categories, function(index, category) {
                    categorySelect.append('<option value="' + category.id + '">' + category.name + '</option>');
                });
                resolve(response);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                reject(new Error("Failed to load categories"));
            },
            complete: function() {
            dropdown.prop('disabled', false);
        }
        });
    });
}

function getAllSubCategories(categoryId = null) {
    const dropdown = $('#js-sub-category-dropdown');
    dropdown.empty().append('<option value="" disabled selected>Loading subcategories...</option>');
    dropdown.prop('disabled', true);
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "{{ route('dropdown.sub-categories') }}",
            type: "GET",
            data: { category_id: categoryId },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {
                var subCategories = response.subCategories;
                var subCategorySelect = $('#js-sub-category-dropdown');
                subCategorySelect.empty();
                subCategorySelect.append('<option value="" selected disabled>Select Sub-Category</option>');
                $.each(subCategories, function(index, subCategory) {
                    subCategorySelect.append('<option value="' + subCategory.id + '">' + subCategory.name + '</option>');
                });
                resolve(response);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                reject(new Error("Failed to load sub-categories"));
            },
            complete: function() {
            dropdown.prop('disabled', false);
        }
        });
    });
}
</script>
@endsection

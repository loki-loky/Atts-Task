<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ATTS Technologies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        .product-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">ATTS Technologies</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('products') ?>">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('categories') ?>">Categories</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Products</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
                Add Product
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table id="productsTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Unique Number</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="productForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="product_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" required autocomplete="off"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" class="form-control" name="price" step="0.01" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-control" name="category_id" required>
                                <option value="" selected disabled>Select Category</option>
                                <?php
                                if (!empty($categories)) {
                                    foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                    </div>
                    <div id="formErrors" class="alert alert-danger d-none"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '<?= base_url('products/getProducts') ?>',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'image',
                        render: function(data) {
                            return data ? `<img src="<?= base_url('public/uploads/') ?>${data}" class="product-image">` : '';
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'price',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: 'category_name'
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-primary edit-product" data-id="${data}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-product" data-id="${data}">Delete</button>
                            `;
                        }
                    }
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Add Product
            $('#productForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = $('#product_id').val();
                var url = id ? `<?= base_url('products/update/') ?>${id}` : '<?= base_url('products/create') ?>';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#productModal').modal('hide');
                            table.ajax.reload();
                            $('#productForm')[0].reset();
                            $('#formErrors').html('').addClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An unexpected error occurred.';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                errorMsg = '<ul>';
                                for (const key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        errorMsg += `<li>${errors[key]}</li>`;
                                    }
                                }
                                errorMsg += '</ul>';
                            } else if (xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                        } else if (xhr.statusText) {
                            errorMsg = xhr.statusText;
                        }

                        $('#formErrors').html(errorMsg).removeClass('d-none');
                    }
                });
            });

            // Edit Product
            $(document).on('click', '.edit-product', function() {
                var id = $(this).data('id');
                $.get(`<?= base_url('products/edit/') ?>/${id}`, function(data) {
                    $('#product_id').val(data.id);
                    $('[name="name"]').val(data.name);
                    $('[name="description"]').val(data.description);
                    $('[name="price"]').val(data.price);
                    $('[name="category_id"]').val(data.category_id);
                    $('#productModal').modal('show');
                });
            });

            // Delete Product
            $(document).on('click', '.delete-product', function() {
                if (confirm('Are you sure you want to delete this product?')) {
                    var id = $(this).data('id');
                    $.post(`<?= base_url('products/delete/') ?>/${id}`, function(response) {
                        if (response.success) {
                            table.ajax.reload();
                        }
                    });
                }
            });

            // Reset form when modal is closed
            $('#productModal').on('hidden.bs.modal', function() {
                $('#productForm')[0].reset();
                $('#product_id').val('');
                $('#formErrors').html('').addClass('d-none');
            });
        });
    </script>
</body>

</html>
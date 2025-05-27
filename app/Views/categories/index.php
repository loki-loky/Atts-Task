<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - ATTS Technologies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
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
            <h2>Categories</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                Add Category
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table id="categoriesTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="categoryForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="category_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required autocomplete="off">
                        </div>
                    </div>
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
            var table = $('#categoriesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '<?= base_url('categories/getCategories') ?>',
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    {
                        data: 'id',
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-primary edit-category" data-id="${data}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-category" data-id="${data}">Delete</button>
                            `;
                        }
                    }
                ],
                //order: [[0, 'desc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Add Category
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var id = $('#category_id').val();
                var url = id ? `<?= base_url('categories/update/') ?>/${id}` : '<?= base_url('categories/create') ?>';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#categoryModal').modal('hide');
                            table.ajax.reload();
                            $('#categoryForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.error);
                    }
                });
            });

            // Edit Category
            $(document).on('click', '.edit-category', function() {
                var id = $(this).data('id');
                $.get(`<?= base_url('categories/edit/') ?>/${id}`, function(data) {
                    $('#category_id').val(data.id);
                    $('[name="name"]').val(data.name);
                    $('#categoryModal').modal('show');
                });
            });

            // Delete Category
            $(document).on('click', '.delete-category', function() {
                if (confirm('Are you sure you want to delete this category?')) {
                    var id = $(this).data('id');
                    $.post(`<?= base_url('categories/delete/') ?>/${id}`, function(response) {
                        if (response.success) {
                            table.ajax.reload();
                        }
                    });
                }
            });

            // Reset form when modal is closed
            $('#categoryModal').on('hidden.bs.modal', function() {
                $('#categoryForm')[0].reset();
                $('#category_id').val('');
            });
        });
    </script>
</body>
</html> 
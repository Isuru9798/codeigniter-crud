<div class="row">
    <div class="col-md-12 mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Grade</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="update_form">
                        <input type="hidden" id="edit_modal_id">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="edit_first_name">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Grade</label>
                            <input type="text" name="grade" class="form-control" id="edit_grade">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="edit_email" name="email" aria-describedby="emailHelp">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footers.php'); ?>
<script>
    function getStudents() {
        $.ajax({
            url: "<?php echo base_url() ?>get-students",
            type: "GET",
            dataType: "json",
            success: function(data) {
                var i = 1;
                var tbody = "";
                for (var key in data) {
                    tbody += "<tr>";
                    tbody += "<td>" + i++ + "</td>";
                    tbody += "<td>" + data[key]['first_name'] + "</td>";
                    tbody += "<td>" + data[key]['last_name'] + "</td>";
                    tbody += "<td>" + data[key]['grade'] + "</td>";
                    tbody += "<td>" + data[key]['email'] + "</td>";
                    tbody += `<td>
                                    <a href="#" id="del" value="${data[key]['id']}">Delete</a>
                                    <a href="#" id="edit" value="${data[key]['id']}">Edit</a>
                                </td>`;
                    tbody += "<tr>";
                }

                $("#tbody").html(tbody);
            }
        });
    }
    getStudents();

    $(document).on("click", "#del", function(e) {
        e.preventDefault();

        var del_id = $(this).attr("value");

        if (del_id == "") {
            alert("Delete id required");
        } else {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger mr-2'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: "<?php echo base_url(); ?>delete",
                        type: "post",
                        dataType: "json",
                        data: {
                            del_id: del_id
                        },
                        success: function(data) {
                            getStudents();
                            if (data.response === 'success') {
                                swalWithBootstrapButtons.fire(
                                    'Deleted!',
                                    'student has been deleted.',
                                    'success'
                                )
                            }
                        }
                    });

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'student is safe :)',
                        'error'
                    )
                }
            });
        }

    });

    $(document).on("click", "#edit", function(e) {
        e.preventDefault();

        var edit_id = $(this).attr("value");

        if (edit_id == "") {
            alert("Edit id required");
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>edit",
                type: "post",
                dataType: "json",
                data: {
                    edit_id: edit_id
                },
                success: function(data) {
                    if (data.response === 'success') {
                        $('#editModal').modal('show');
                        $("#edit_modal_id").val(data.post.id);
                        $("#edit_first_name").val(data.post.first_name);
                        $("#edit_last_name").val(data.post.last_name);
                        $("#edit_grade").val(data.post.grade);
                        $("#edit_email").val(data.post.email);
                    } else {
                        Command: toastr["error"](data.message)

                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                }
            });
        }
    });
    $(document).on("click", "#update", function(e) {
        e.preventDefault();

        var edit_id = $("#edit_modal_id").val();
        var edit_first_name = $("#edit_first_name").val();
        var edit_last_name = $("#edit_last_name").val();
        var edit_grade = $("#edit_grade").val();
        var edit_email = $("#edit_email").val();

        if (edit_id == "" || edit_first_name == "" || edit_last_name == "" || edit_grade == "" || edit_email == "") {
            alert("both field is required");
        } else {

            $.ajax({
                url: "<?php echo base_url(); ?>update",
                type: "post",
                dataType: "json",
                data: {
                    edit_id: edit_id,
                    edit_first_name: edit_first_name,
                    edit_last_name: edit_last_name,
                    edit_grade: edit_grade,
                    edit_email: edit_email,
                },
                success: function(data) {
                    console.log(data);
                    getStudents();
                    if (data.response === 'success') {
                        $('#editModal').modal('hide');
                        toastr["success"](data.message)

                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    } else {
                        toastr["error"](data.message)

                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                }
            });
            $("#update_form")[0].reset();
        }

    });
</script>
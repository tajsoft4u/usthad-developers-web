<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">All Category</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <button type="button" class="waves-effect waves-light btn mb-1 bg-gradient-primary float-right" data-toggle="modal" data-target=".bs-example-modal-lg">Add Category</button>
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Category</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($categories)) { ?>
                                            <?php $i = 1;
                                            foreach ($categories->result() as $row) { ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row->category; ?></td>
                                                    <td>
                                                        <button class="waves-effect waves-light btn mb-5 bg-gradient-primary" data-toggle="modal" data-target=".editModal" onclick="setDataFunction('<?php echo $row->catId; ?>',
                                                            '<?php echo $row->category; ?>',
                                                            )"><i class=" ti-pencil-alt"></i>
                                                        </button>
                                                        <button class="waves-effect waves-light btn mb-5 bg-gradient-danger" data-toggle="modal" data-target=".deleteModal" onclick="setDeleteFunction('<?php echo $row->catId; ?>')"> <i class=" ti-trash"></i></button>
                                                    </td>
                                                </tr>
                                        <?php $i++;
                                            }
                                        } ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.modal -->

        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="addForm" method="post" action="<?php echo base_url('Master/categoryAdd') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add Category</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label col-md-2">Category <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="text" name="category" id="category" placeholder="category">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary text-left">Save</button>
                            <button type="button" class="btn btn-danger text-left" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade editModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editForm" method="post" action="<?php echo base_url('Master/categoryEdit') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Edit Category</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <input type="hidden" name="ecatId" id="ecatId">
                        <div class="modal-body">
                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label col-md-2">Category <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="text" name="ecategory" id="ecategory" placeholder="location" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary text-left">Update</button>
                            <button type="button" class="btn btn-danger text-left" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="myModal" class="modal fade deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="<?php echo base_url('Master/categoryDelete') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Delete Category</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <input type="hidden" name="dcatId" id="dcatId">
                        <div class="modal-body">
                            <h4>Are you sure to delete this ?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary text-left">Yes</button>
                            <button type="button" class="btn btn-danger text-left" data-dismiss="modal">No</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
</div>



<script>
    function setDataFunction(catId, category) {
        $('#ecatId').val(catId);
        $('#ecategory').val(category);
        $('.editModal').modal('show');
    }
</script>

<script>
    function setDeleteFunction(catId) {
        $('#dcatId').val(catId);
        $('.deleteModal').modal('show');
    }
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#addForm').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                category: {
                    validators: {
                        notEmpty: {
                            message: 'The category is required'
                        },
                    }
                },
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#editForm').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                category: {

                    validators: {
                        notEmpty: {
                            message: 'The category is required'
                        },
                    }
                },
            }
        });
    });
</script>
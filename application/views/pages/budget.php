<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">All Budget</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <button type="button" class="waves-effect waves-light btn mb-1 bg-gradient-primary float-right" data-toggle="modal" data-target=".bs-example-modal-lg">Add Budget</button>
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                    <thead>
                                        <tr>
                                            <th>Budget</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($budgets)) { ?>
                                            <?php foreach ($budgets->result() as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row->budget_name; ?></td>
                                                    <td>
                                                        <button class="waves-effect waves-light btn mb-5 bg-gradient-primary" data-toggle="modal" data-target=".editModal" onclick="setDataFunction('<?php echo $row->bId; ?>',
                                                            '<?php echo $row->budget_name; ?>',
                                                            )"><i class=" ti-pencil-alt"></i>
                                                        </button>
                                                        <button class="waves-effect waves-light btn mb-5 bg-gradient-danger" data-toggle="modal" data-target=".deleteModal" onclick="setDeleteFunction('<?php echo $row->bId; ?>')"> <i class=" ti-trash"></i></button>
                                                    </td>
                                                </tr>
                                        <?php }
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
                    <form id="addForm" method="post" action="<?php echo base_url('Master/budgetAdd') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add Budget</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label col-md-2">Budget <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="text" name="budget_name" id="budget_name" placeholder="Budget">
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
                    <form id="editForm" method="post" action="<?php echo base_url('Master/budgetEdit') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Edit Budget</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <input type="hidden" name="ebId" id="ebId">
                        <div class="modal-body">
                            <div class="form-group row">

                                <div class="col-md-10">

                                    <label class="col-form-label col-md-2">Budget <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="text" name="ebudget_name" id="ebudget_name" placeholder="location" required>
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
                    <form method="post" action="<?php echo base_url('Master/budgetDelete') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Delete Budget</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <input type="hidden" name="dbId" id="dbId">
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
    function setDataFunction(bId, budget_name) {

        $('#ebId').val(bId);
        $('#ebudget_name').val(budget_name);
        $('.editModal').modal('show');
    }
</script>

<script>
    function setDeleteFunction(bId) {
        $('#dbId').val(bId);
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
                budget_name: {
                    validators: {
                        notEmpty: {
                            message: 'The Budget is required'
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
                ebudget_name: {
                    validators: {
                        notEmpty: {
                            message: 'The Budget is required'
                        },
                    }
                },
            }
        });
    });
</script>
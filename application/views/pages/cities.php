<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">All Cities</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <button type="button" class="waves-effect waves-light btn mb-1 bg-gradient-primary float-right" data-toggle="modal" data-target=".bs-example-modal-lg">Add Cities</button>
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>District</th>
                                            <th>City</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($cities)) { ?>
                                            <?php $i = 1;
                                            foreach ($cities->result() as $row) { ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row->location; ?></td>
                                                    <td><?php echo $row->cname; ?></td>
                                                    <td>
                                                        <button class="waves-effect waves-light btn mb-5 bg-gradient-primary" data-toggle="modal" data-target=".editModal" onclick="setDataFunction('<?php echo $row->cId; ?>',
                                                        '<?php echo $row->districtId; ?>','<?php echo $row->cname; ?>',
                                                            )"><i class=" ti-pencil-alt"></i>
                                                        </button>
                                                        <button class="waves-effect waves-light btn mb-5 bg-gradient-danger" data-toggle="modal" data-target=".deleteModal" onclick="setDeleteFunction('<?php echo $row->cId; ?>')"> <i class=" ti-trash"></i></button>

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
                    <form id="addForm" method="post" action="<?php echo base_url('Master/citiesAdd') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add Cities</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label class="col-form-label col-md-2">Location</label>
                                    <select class="form-control select2" name="district" id="district" style="width: 100%;" required>
                                        <option selected="" disabled="">Select</option>
                                        <?php if ($locations->num_rows() > 0) {
                                            foreach ($locations->result() as $row) { ?>
                                                <option value="<?php echo $row->locId ?>"><?php echo $row->location ?></option>
                                        <?php }
                                        } ?>

                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="col-form-label col-md-2">Cities </label>
                                    <input class="form-control" type="text" name="cname" id="cname" placeholder="cities" required>
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
                    <form method="post" action="<?php echo base_url('Master/citiesEdit') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Edit Cities</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <input type="hidden" name="editId" id="editId">
                        <div class="modal-body">
                            <div class="form-group row">

                            <div class="col-md-5">
                                    <label class="col-form-label col-md-2">Location</label>
                                    <select class="form-control select2" name="edistrict" id="edistrict" style="width: 100%;" required>
                                        <option selected="" disabled="">Select</option>
                                        <?php if ($locations->num_rows() > 0) {
                                            foreach ($locations->result() as $row) { ?>
                                                <option value="<?php echo $row->locId ?>"><?php echo $row->location ?></option>
                                        <?php }
                                        } ?>

                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="col-form-label col-md-2">Cities </label>
                                    <input class="form-control" type="text" name="ecity" id="ecity" placeholder="cities" required>
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
                    <form method="post" action="<?php echo base_url('Master/citiesDelete') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Delete Location</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <input type="hidden" id="deleteId" name="deleteId" />
                        </div>
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
    function setDataFunction(locId, district,cname) {
        $('#editId').val(locId);
        $('#edistrict').val(district);
        $('#ecity').val(cname);
        $('.editModal').modal('show');
    }
</script>

<script>
    function setDeleteFunction(locId) {
        $('#deleteId').val(locId);
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
                elocation: {

                    validators: {
                        notEmpty: {
                            message: 'The location is required'
                        },
                    }
                },
            }
        });
    });
</script>
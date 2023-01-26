<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Area List</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <a href="<?php echo base_url('add-area') ?>">
                                <button type="button" class="waves-effect waves-light btn mb-1 bg-gradient-primary float-right" data-toggle="modal" data-target=".bs-example-modal-lg">Add Cities</button>
                            </a>
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
                                        <?php if (!empty($areas)) { ?>
                                            <?php $i = 1;
                                            foreach ($areas->result() as $row) { ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row->cname; ?></td>
                                                    <td><?php echo $row->aname; ?></td>
                                                    <td>
                                                        <a href="<?php echo base_url('edit-area/' . $row->aId) ?>">
                                                            <button class="waves-effect waves-light btn mb-5 bg-gradient-primary"><i class=" ti-pencil-alt"></i></button>
                                                        </a>

                                                        <button class="waves-effect waves-light btn mb-5 bg-gradient-danger" data-toggle="modal" data-target=".deleteModal" onclick="setDeleteFunction('<?php echo $row->aId; ?>')"> <i class=" ti-trash"></i></button>

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

            <div id="myModal" class="modal fade deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?php echo base_url('Master/deleteArea') ?>" method="post">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Delete Area</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <input type="hidden" name="deleteId" id="deleteId">
                            <div class="modal-body">
                                <div class="row">
                                    <h4>Are you sure to delete this ?</h4>
                                </div>

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
        </section>
    </div>
</div>
<!-- /.modal -->

<script>
    function setDeleteFunction(prodId) {

        $('#deleteId').val(prodId);
        $('.deleteModal').modal('show');
    }
</script>
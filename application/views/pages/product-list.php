<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">All Products</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <a href="<?php echo base_url('add-product') ?>">
                                <button type="button" class="waves-effect waves-light btn mb-1 bg-gradient-primary float-right">Add Product</button></a>
                            <div class="table-responsive">
                                <table id="testTable" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>title</th>
                                            <!--<th>Description</th>-->
                                            <th>Location</th>
                                            <th>Category</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($products)) { ?>
                                            <?php $i = 1;
                                            foreach ($products->result() as  $row) { ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row->title; ?></td>

                                                    <td><?php echo $row->location; ?></td>
                                                    <td><?php echo $row->category; ?></td>

                                                    <td>
                                                        <img src="<?php echo $row->imageUrl; ?>" width="100" height="100" />
                                                    </td>

                                                    <td>
                                                        <a href="<?php echo base_url('edit-product/' . $row->prodId) ?>">
                                                            <button class="waves-effect waves-light btn mb-5 bg-gradient-primary"><i class=" ti-pencil-alt"></i>
                                                            </button> </a>
                                                        <button class="waves-effect waves-light btn mb-5 bg-gradient-danger" data-toggle="modal" data-target=".deleteModal" onclick="setDeleteFunction('<?php echo $row->prodId; ?>')"> <i class=" ti-trash"></i></button>

                                                    </td>
                                                </tr>
                                        <?php $i++;
                                            }
                                        } ?>

                                    </tbody>
                                </table>
                                <p class="pull-right" style="font-size: 10px"><?php echo $links; ?></p>
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
                    <form enctype="multipart/form-data" method="post" action="<?php echo base_url('Master/productAdd') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add Products</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label ">Title <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="text" name="title" placeholder="title" required>
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label ">Description <span class="danger" style="color: 'red';">*</span></label>
                                    <textarea class="form-control" type="text" name="description" placeholder="description" required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-5">
                                    <label class="col-form-label ">Location <span class="danger" style="color: 'red';">*</span></label>
                                    <!-- <input class="form-control" type="text" name="location" placeholder="location"> -->
                                    <select class="form-control select2" name="location" id="location" style="width: 100%;" required>

                                        <option selected="" disabled="">Select</option>
                                        <?php if ($locations->num_rows() > 0) {
                                            foreach ($locations->result() as $row) { ?>
                                                <option value="<?php echo $row->locId ?>"><?php echo $row->location ?></option>
                                        <?php }
                                        } ?>

                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="col-form-label ">City <span class="danger" style="color: 'red';">*</span></label>
                                    <!-- <input class="form-control" type="text" name="location" placeholder="location"> -->
                                    <select class="form-control select2" name="pcity" id="pcity" style="width: 100%;" required>

                                        <option selected="" disabled="">Select</option>
                                        <?php if ($cities->num_rows() > 0) {
                                            foreach ($cities->result() as $row) { ?>
                                                <option value="<?php echo $row->cId ?>"><?php echo $row->cname ?></option>
                                        <?php }
                                        } ?>

                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label class="col-form-label ">Category <span class="danger" style="color: 'red';">*</span></label>
                                    <!--  <input class="form-control" type="text" name="category" placeholder="category"> -->
                                    <select class="form-control select2" style="width: 100%;" name="category" id="category" required>

                                        <option selected="" disabled="">Select</option>
                                        <?php if ($categories->num_rows() > 0) {
                                            foreach ($categories->result() as $row) { ?>
                                                <option value="<?php echo $row->catId ?>"><?php echo $row->category ?></option>
                                        <?php }
                                        } ?>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label ">Main Image <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="file" name="image" required>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label ">Feature Images <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="file" required name="files[]" multiple>
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
                <div class="modal-content ">
                    <form method="post" action="<?php echo base_url('Master/productEdit') ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Edit Products</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <input class="form-control" type="hidden" name="prodId" id="prodId" placeholder="title">
                                <div class="col-md-10">
                                    <label class="col-form-label col-md-2">Title <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="text" name="etitle" id="etitle" placeholder="title">
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label col-md-2">Description <span class="danger" style="color: 'red';">*</span></label>
                                    <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-5">
                                    <label class="col-form-label col-md-2">Location <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="text" id="elocation" name="elocation" placeholder="location" required>

                                </div>

                                <div class="col-md-5">
                                    <label class="col-form-label col-md-2">Category <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="text" name="ecategory" id="ecategory" placeholder="category" required>

                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-10">
                                    <label class="col-form-label col-md-2">Image <span class="danger" style="color: 'red';">*</span></label>
                                    <input class="form-control" type="file" name="eimage" id="eimage" placeholder="title" required>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary text-left">Update</button>
                            <button type="button" class="btn btn-danger text-left" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div id="myModal" class="modal fade deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo base_url('Master/productDelete') ?>" method="post">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Delete Product</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <input type="hidden" name="dprodId" id="dprodId">
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



<!-- Back  -->
<script type="text/javascript">
    2
    $(document).ready(function() {
        3
        $('.textarea').wysihtml5();
        4
    });
    5
</script>

<script>
    function setDataFunction(prodId, title, description, location, category) {
        $('#prodId').val(prodId);
        $('#etitle').val(title);
        $('#edescription').val(description);
        $('#elocation').val(location);
        $('#ecategory').val(category);
        $('.editModal').modal('show');
    }
</script>

<script>
    function setDeleteFunction(prodId) {
        // alert(prodId)
        $('#dprodId').val(prodId);
        $('.deleteModal').modal('show');
    }
</script>
<script>
    (function() {
        "use strict";
        $('#testTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ],
            'paging': false,
            'lengthChange': false,
            'searching': false,
            'ordering': true,
            'info': true,
            'autoWidth': false
        });
    });
</script>


<script type="text/javascript">
    $('#testTable').dataTable({
        "search": {
            "search": ""
        },
        bPaginate: false,
    });
    // (document).ready(function(){
    //     var table = $('#testTable').DataTable( {
    //         lengthChange: false,
    //         bPaginate: false,
    //         lengthMenu: [
    //         [10, 25, 50, -1],
    //         [10, 25, 50, 'All'],
    //     ],
    //         buttons: [
    //         {
    //             extend: 'pdf',
    //             footer: true,
    //             exportOptions: {
    //                 columns: [0,1,2,3,4]
    //             }
    //         },
    //         {
    //             extend: 'excel',
    //             footer: true,
    //             exportOptions: {
    //                 columns: [0,1,2,3,4]
    //             }
    //         }         
    //         ], 
    //         "order": [[ 4, "desc" ]],

    //         responsive: true,
    //         language: {
    //             searchPlaceholder: 'Search from below table...',
    //             sSearch: '',
    //         // lengthMenu: '_MENU_ items/page',
    //     }
    // });
    //     table.buttons().container()
    //     .appendTo( '#testTable_wrapper .col-md-6:eq(0)' );
    // })
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Products</h3>
                        </div>
                    </div>
                    <div>
                        <div class="body">
                            <div class="modal-content ">
                                <form id="addForm" enctype="multipart/form-data" method="post" action="<?php echo base_url('Master/productEdit') ?>">

                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <input class="form-control" type="hidden" name="prodId" id="prodId" value="<?php if ($product->prodId != '') echo $product->prodId ?>">
                                            <div class="col-md-10">
                                                <label class="col-form-label col-md-2">Title <span class="danger" style="color: 'red';">*</span></label>
                                                <input class="form-control" type="text" name="etitle" id="etitle" placeholder="title" value="<?php if ($product->title != null) echo $product->title ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <div class="col-md-10">
                                                <label class="col-form-label col-md-2">Description <span class="danger" style="color: 'red';">*</span></label>
                                                <textarea class="form-control" type="text" name="edescription" id="edescription" placeholder="description" onkeyup="textAreaAdjust(this)" style="overflow:hidden" required><?php if ($product->description != null) echo $product->description ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                <label class="col-form-label col-md-2">Location*</label>

                                                <select class="form-control select2" name="elocation" id="elocation" style="width: 100%;" required>
                                                    <?php if ($locations->num_rows() > 0) {
                                                        foreach ($locations->result() as $row) { ?>
                                                            <option value="<?php echo $row->locId ?>" <?php if ($product != '') {
                                                                                                            echo ($product->location == $row->locId) ? "selected" : " ";
                                                                                                        } ?>>
                                                                <?php echo $row->location ?>
                                                            </option>
                                                    <?php }
                                                    } ?>

                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label col-md-2">City *</label>

                                                <select class="form-control select2" name="ecity" id="ecity" style="width: 100%;" required>
                                                    <option selected="" disabled="">Select</option>
                                                    <?php if ($cities->num_rows() > 0) {
                                                        foreach ($cities->result() as $row) { ?>
                                                            <option value="<?php echo $row->cId ?>" <?php if ($product != '') {
                                                                                                        echo ($product->pcity == $row->cId) ? 'selected' : '';
                                                                                                    } ?>><?php echo $row->cname ?></option>
                                                    <?php }
                                                    } ?>

                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label col-md-2">Area *</label>

                                                <select class="form-control select2" name="earea" id="earea" style="width: 100%;" required>
                                                    <option selected="" disabled="">Select</option>
                                                    <?php if ($areas->num_rows() > 0) {
                                                        foreach ($areas->result() as $row) { ?>
                                                            <option value="<?php echo $row->aId ?>" <?php if ($product != '') {
                                                                                                        echo ($product->parea == $row->aId) ? 'selected' : '';
                                                                                                    } ?>><?php echo $row->aname ?></option>
                                                    <?php }
                                                    } ?>

                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label col-md-2">Category*</label>
                                                <select class="form-control select2" name="ecategory" id="ecategory" style="width: 100%;" required>
                                                    <!--<option selected="" disabled="">Select</option>-->
                                                    <?php if ($categories->num_rows() > 0) {
                                                        foreach ($categories->result() as $row) { ?>
                                                            <option value="<?php echo $row->catId ?>" <?php if ($product != '') {
                                                                                                            echo ($product->category == $row->catId) ? 'selected' : '';
                                                                                                        } ?>><?php echo $row->category ?></option>
                                                    <?php }
                                                    } ?>

                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-form-label col-md-2">Budget*</label>
                                                <select class="form-control select2" name="ebudget" id="ebudget" style="width: 100%;" required>
                                                    <?php if ($budget->num_rows() > 0) {
                                                        foreach ($budget->result() as $row) { ?>
                                                            <option value="<?php echo $row->bId ?>" <?php if ($budget != '') {
                                                                                                        echo ($product->pbudget == $row->bId) ? 'selected' : '';
                                                                                                    } ?>><?php echo $row->budget_name ?></option>
                                                    <?php }
                                                    } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">

                                            <div class="col-md-10">
                                                <label class="col-form-label col-md-2">Image <span class="danger" style="color: 'red';">*</span></label>
                                                <input type="file" class="form-control" name="image" id="image">
                                                <input class="form-control" type="hidden" name="old_photo" id="old_photo" value="<?php if ($product->imageUrl != null) echo $product->imageUrl ?>">

                                                <img src="<?php echo $product->imageUrl; ?>" width="100" height="100" style="margin-top:20px" ; />
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <label class="col-form-label col-md-2">Feature Image </label>
                                            <input type="file" class="form-control" name="files[]" id="files" multiple>
                                        </div>
                                        <div class="text-center" style="padding-top:20px;">
                                            <button type="submit" class="btn btn-primary text-left">Update</button>

                                        </div>
                                </form>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <h3>Feature Images</h3>
                                        <table id="example1" class="table table-bordered table-striped" data-page-length='4'>
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Action</th>
                                            </thead>
                                            </tr>

                                            <tbody>
                                                <?php if (!empty($featureImages)) { ?>
                                                    <?php $i = 1;
                                                    foreach ($featureImages as  $row) { ?>
                                                        <tr>
                                                            <td> <img src="<?php echo $row->featureImage; ?>" width="50" height="50" style="margin-top:20px" ;>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-primary" data-toggle="modal" data-target=".editModal" onclick="setDataFunction('<?php echo $row->featureId; ?>',
                                                                            '<?php echo $row->imageId; ?>',
                                                                            '<?php echo $row->featureImage; ?>','<?php echo $row->productId; ?>')"><i class=" ti-pencil-alt"></i>
                                                                </button>
                                                                <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-danger" data-toggle="modal" data-target=".deleteModal" onclick="setDeleteFunction('<?php echo $row->featureId; ?>','<?php echo $row->featureImage; ?>','<?php echo $row->productId; ?>')"> <i class=" ti-trash"></i></button>

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
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <div class="modal fade editModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content ">
                            <form enctype="multipart/form-data" method="post" action="<?php echo base_url('Master/editFeatureImage') ?>">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Edit Products</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <div class="col-md-10">
                                            <input type="hidden" name="eId" id="eId" />
                                            <input type="hidden" name="efeatureId" id="efeatureId" />
                                            <input type="hidden" name="prodEId" id="prodEId" value="" />
                                            <label class="col-form-label col-md-2">Image <span class="danger" style="color: 'red';">*</span></label>
                                            <input class="form-control" type="file" name="featureImage" id="featureImage" placeholder="title" required>
                                            <!-- <img src="<?php echo $row->imageUrl; ?>"style="width:50;height: 50;"/> -->
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
                            <form action="<?php echo base_url('Master/deleteFeaturImages') ?>" method="post">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Delete Product</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <input type="hidden" name="dfeatureId" id="dfeatureId">
                                <input type="hidden" name="dfeatureImage" id="dfeatureImage">
                                <input type="hidden" name="dproductId" id="dproductId">
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
    </section>
</div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#addForm').bootstrapValidator({
            fields: {
                etitle: {
                    validators: {
                        notEmpty: {
                            message: 'The title is required'
                        },
                    }
                },
                edescription: {
                    validators: {
                        notEmpty: {
                            message: 'The description is required'
                        },
                    }
                },
                elocation: {
                    validators: {
                        notEmpty: {
                            message: 'The location is required'
                        },
                    }
                },
                ecity: {
                    validators: {
                        notEmpty: {
                            message: 'The city is required'
                        },
                    }
                },
                earea: {
                    validators: {
                        notEmpty: {
                            message: 'The area is required'
                        },
                    }
                },
                ecategory: {
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
<script>
    function textAreaAdjust(element) {
        element.style.height = "1px";
        element.style.height = (25 + element.scrollHeight) + "px";
    }
</script>
<script>
    function setDataFunction(featureId, imageId, featureImage, productId) {
        // alert(productId)
        $('#eId').val(featureId);
        $('#efeatureId').val(imageId);
        //  $('#featureImage').val(featureImage);
        $('#prodEId').val(productId);

        $('.editModal').modal('show');
    }
</script>
<script>
    function setDeleteFunction(featureId, featureImage, productId) {

        $('#dfeatureId').val(featureId);
        $('#dfeatureImage').val(featureImage);
        $('#dproductId').val(productId);
        $('.deleteModal').modal('show');
    }
</script>
<script type="text/javascript">
    2
    $(document).ready(function() {
        3
        $('.textarea').wysihtml5();
        4
    });
    5
</script>
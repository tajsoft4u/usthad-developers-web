<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Area</h3>
                        </div>
                    </div>

                    <div class="body">
                        <div class="modal-content ">
                            <form enctype="multipart/form-data" method="post" action="<?php echo base_url('Master/addArea') ?>">

                                <div class="modal-body">
                                    <div class="form-group row">

                                        <div class="col-md-8">
                                            <label class="col-form-label col-md-2">City</label>
                                            <select class="form-control select2" name="acity" id="acity" style="width: 100%;" required>
                                                <option selected="" disabled="">Select</option>
                                                <?php if ($cities->num_rows() > 0) {
                                                    foreach ($cities->result() as $row) { ?>
                                                        <option value="<?php echo $row->cId ?>">
                                                            <?php echo $row->cname ?>
                                                        </option>
                                                <?php }
                                                } ?>

                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="col-form-label col-md-2">Title <span class="danger" style="color: 'red';">*</span></label>
                                            <textarea class="form-control" type="text" name="aname" id="aname" onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea>
                                        </div>
                                    </div>

                                    <button type="submit" class="waves-effect waves-light btn mb-5 bg-gradient-danger">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    function textAreaAdjust(element) {
        element.style.height = "1px";
        element.style.height = (25 + element.scrollHeight) + "px";
    }
</script>
<!-- Content Wrapper. Contains page content -->
<style>
    textarea[contenteditable] {
        border: 1px solid black;
        max-height: 200px;
        overflow: auto;
    }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Area</h3>
                        </div>
                    </div>

                    <div class="body">
                        <div class="modal-content ">
                            <form enctype="multipart/form-data" method="post" action="<?php echo base_url('Master/editArea') ?>">

                                <div class="modal-body">
                                    <div class="form-group row">
                                        <input class="form-control" type="hidden" name="editId" id="editId" value="<?php if ($area->aId != '') echo $area->aId ?>">
                                        <div class="col-md-8">
                                            <label class="col-form-label col-md-2">City</label>
                                            <select class="form-control select2" name="acity" id="acity" style="width: 100%;" required>
                                                <?php if ($cities->num_rows() > 0) {
                                                    foreach ($cities->result() as $row) { ?>
                                                        <option value="<?php echo $row->cId ?>" <?php if ($area != '') {
                                                                                                    echo ($area->aCity == $row->cId) ? "selected" : " ";
                                                                                                } ?>>
                                                            <?php echo $row->cname ?>
                                                        </option>
                                                <?php }
                                                } ?>

                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="col-form-label col-md-2">Title <span class="danger" style="color: 'red';">*</span></label>
                                            <textarea class="form-control" type="text" name="aname" id="aname" placeholder="title" onkeyup="textAreaAdjust(this)" style="overflow:hidden"><?php if ($area->aname != null) echo $area->aname ?>
                                            </textarea>
                                        </div>
                                    </div>

                                    <button type="submit" class="waves-effect waves-light btn mb-5 bg-gradient-primary">Submit</button>
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
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Product</h3>
                        </div>
                    </div>
<div class="card">
                  <div class="box-body">
                            <form id="addForm"  enctype="multipart/form-data" method="post" action="<?php echo base_url('Master/productAdd')?>">
                                        <div class="form-group row">

                                            <div class="col-md-10">
                                               <label class="col-form-label ">Title <span class="danger"style="color: 'red';">*</span></label>
                                               <input class="form-control" type="text" name="title" placeholder="title"required>
                                           </div>
                                       </div>

                                       <div class="form-group row">

                                        <div class="col-md-10">
                                           <label class="col-form-label ">Description <span class="danger"style="color: 'red';">*</span></label>
                                           <textarea class="form-control" rows="5" type="text" name="description" placeholder="description" onkeyup="textAreaAdjust(this)" style="overflow:hidden" required></textarea> 

                                       </div>
                                   </div>
                                   <div class="form-group row">

                                    <div class="col-md-5">
                                       <label class="col-form-label ">Location <span class="danger"style="color: 'red';">*</span></label>
                                       <!-- <input class="form-control" type="text" name="location" placeholder="location"> -->
                                       <select class="form-control select2" name="location" id="location" style="width: 100%;" required>

                                        <option selected="" disabled="">Select</option>
                                        <?php if($locations->num_rows()>0){ foreach($locations->result() as $row){?>
                                            <option value="<?php echo $row->locId?>"><?php echo $row->location?></option>
                                        <?php }}?>

                                    </select>
                                </div>
                                <div class="col-md-5">
                                       <label class="col-form-label ">City <span class="danger"style="color: 'red';">*</span></label>
                                       <!-- <input class="form-control" type="text" name="location" placeholder="location"> -->
                                       <select class="form-control select2" name="pcity" id="pcity" style="width: 100%;" required>

                                        <option selected="" disabled="">Select</option>
                                        <?php if($cities->num_rows()>0){ foreach($cities->result() as $row){?>
                                            <option value="<?php echo $row->cId?>"><?php echo $row->cname?></option>
                                        <?php }}?>

                                    </select>
                                </div>
                                <div class="col-md-5">
                                       <label class="col-form-label ">Area <span class="danger"style="color: 'red';">*</span></label>
                                       <!-- <input class="form-control" type="text" name="location" placeholder="location"> -->
                                       <select class="form-control select2" name="parea" id="parea" style="width: 100%;" required>
                                        <option selected="" disabled="">Select</option>
                                        <?php if($areas->num_rows()>0){ foreach($areas->result() as $row){?>
                                            <option value="<?php echo $row->aId?>"><?php echo $row->aname?></option>
                                        <?php }}?>

                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label class="col-form-label ">Category <span class="danger"style="color: 'red';">*</span></label>
                                   <!--  <input class="form-control" type="text" name="category" placeholder="category"> -->
                                      <select class="form-control select2" style="width: 100%;" name="category" id="category" required>
                                        
                                        <option selected="" disabled="">Select</option>
                                        <?php if($categories->num_rows()>0){ foreach($categories->result() as $row){?>
                                            <option value="<?php echo $row->catId?>"><?php echo $row->category?></option>
                                        <?php }}?>

                                    </select>
                                </div>
                                 <div class="col-md-5">
                                    <label class="col-form-label ">Budget <span class="danger"style="color: 'red';">*</span></label>
                                      <select class="form-control select2" style="width: 100%;" name="budget" id="budget" required>
                                        <option selected="" disabled="">Select</option>
                                        <?php if($budget->num_rows()>0){ foreach($budget->result() as $row){?>
                                            <option value="<?php echo $row->bId?>"><?php echo $row->budget_name?></option>
                                        <?php }}?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-10">
                                   <label class="col-form-label ">Main Image <span class="danger"style="color: 'red';">*</span></label>
                                   <input class="form-control" type="file" name="image"required >
                               </div>
                           </div>
                             <div class="form-group row">

                                <div class="col-md-10">
                                   <label class="col-form-label ">Feature Images <span class="danger"style="color: 'red';">*</span></label>
                                   <input class="form-control" type="file" required name="files[]" multiple>
                               </div>
                           </div>

                       
                      
                        <button type="submit" class="waves-effect waves-light btn mb-5 bg-gradient-danger float-right">
                                       Submit
                                   </button> 
                      
                  
                </form>

                        </div></div>
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
                title: {
                    validators: {
                        notEmpty: {
                            message: 'The title is required'
                        },
                    }
                },
                description: {
                    validators: {
                        notEmpty: {
                            message: 'The description is required'
                        },
                    }
                },
                location: {
                    validators: {
                        notEmpty: {
                            message: 'The location is required'
                        },
                    }
                },
                pcity: {
                    validators: {
                        notEmpty: {
                            message: 'The city is required'
                        },
                    }
                },
                parea: {
                    validators: {
                        notEmpty: {
                            message: 'The area is required'
                        },
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: 'The category is required'
                        },
                    }
                },
                budget:{
                    validators: {
                        notEmpty: {
                            message: 'The budget is required'
                        },
                    }
                },
                image: {
                    validators: {
                        notEmpty: {
                            message: 'The image is required'
                        },
                    }
                },
                files: {
                    validators: {
                        notEmpty: {
                            message: 'The feature images is required'
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
  element.style.height = (25+element.scrollHeight)+"px";
}
</script>
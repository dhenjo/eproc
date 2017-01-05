<style>
    .working{
        background:url('<?php print $url?>img/ajax-loader.gif') no-repeat right center;
        background-size: 20px;
    }
</style>
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php 
            
            $akses = "normal";
            $kunci = TRUE;
            if($detail[0]->status > 1){
              $akses = "view";
              $kunci = FALSE;
            }
            
//            if($akses != "view"){
              print $this->form_eksternal->form_open("", 'role="form"', array("id_detail" => $detail[0]->id_mrp_brand));
//            }
            ?>
              <div class="box-body">

                <div class="control-group">
                  <label>title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'class="form-control input-sm" placeholder="Title"');?>
                </div>
                
                <div class="control-group">
                  <label>Code</label>
                  <?php print $this->form_eksternal->form_input('code', $detail[0]->code, 'class="form-control input-sm" placeholder="Code"');?>
                </div>
                <div class="control-group">
                    <h5><b>Status</b></h5>
                    <div class="input-group">
                        <div class="checkbox">
                            <label>
                                <?php
                                if($detail[0]->status == 1)
                                  print $this->form_eksternal->form_checkbox('status', 1, TRUE);
                                else
                                  print $this->form_eksternal->form_checkbox('status', 1, FALSE);
                                ?>
                                Active
                            </label>
                        </div>
                        </div>
                </div>
              </div>
              <div class="box-footer">
                 
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  
                  <a href="<?php print site_url("mrp/mrp-master/brand")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

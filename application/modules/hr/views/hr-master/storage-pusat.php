<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => $detail[0]->id_hr_company))?>
              <div class="box-body">
                  <?php 
                  if($detail[0]->title){
                     $direktorat = $detail[0]->title." <".$detail[0]->code.">";
                  }
                  
                  ?>
                <div class="control-group">
                  <label>Direktorat</label>
                 
                  <div class="control-group" id="direktorat-box">
                      <input type="text" class="form-control" id="direktorat" name="direktorat" value="<?php print $direktorat; ?>" style="width: 100%">
                      <input type="text" class="form-control" id="id_direktorat" name="id_direktorat" value="<?php print $detail[0]->storage_pusat; ?>" style="display: none">
<!--                      <span class="input-group-btn">
                          <a href="javascript:void(0)" class="btn btn-danger btn-flat delete" isi="direktorat-box" >
                            <i class="fa fa-fw fa-times"></i>
                          </a>
                      </span>-->
                  </div>
                </div>
               
              </div>
              <div class="box-footer">
                <input type="text" class="form-control" value="<?php print $detail?>" id="nomor" style="display: none">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("hr/hr-master/company")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
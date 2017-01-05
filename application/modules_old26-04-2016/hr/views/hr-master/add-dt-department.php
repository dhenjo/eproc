<?php
//print "<pre>";
//print
//print_r($list);
//print "</pre>";
//die;
?>
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
                    array("id_detail" => $detail[0]->id_hr_master_organisasi))?>
              <div class="box-body">
                
                <div class="control-group">
                  <label>Title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'class="form-control input-sm" placeholder="Title"');?>
                </div>
                <div class="control-group">
                  <label>Code</label>
                  <?php print $this->form_eksternal->form_input('code', $detail[0]->code, 'class="form-control input-sm" placeholder="Code"');?>
                </div>
                 <div class="control-group">
                  <label>Direktorat</label>
                  <?php print $this->form_eksternal->form_dropdown("direktorat", $direktorat, 
                    $detail[0]->direktorat, 'id="id_direktorat" class="form-control dropdown2 input-sm"');?>
                </div>
                <span id="dt_department">
                <div class="control-group">
                  <label>Divisi</label>
                 <?php print $this->form_eksternal->form_dropdown("divisi", "", 
                   $detail[0]->department, ' class="form-control dropdown2 input-sm"');?>
                </div>
                   </span>  
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("hr/hr-master/dt-department")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

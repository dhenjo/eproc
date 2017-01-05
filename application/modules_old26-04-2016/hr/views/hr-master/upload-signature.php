<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php print $this->form_eksternal->form_open_multipart("", 'role="form"', 
                    array("id_detail" => $id_hr_pegawai))?>
              <div class="box-body">
                <?php if($signature){?>
                <div class="control-group">
                  <label>Signature</label><br>
                  <img style="width: 120px;height: 70px;" src="<?php print base_url()."files/antavaya/signature/{$signature}"?>"
                   ?>
                </div>
                <?php } ?>
                <div class="control-group">
                  <label>File</label>
                  <?php print $this->form_eksternal->form_upload('file', $detail[0]->file, "class='form-control input-sm'");
                   ?>
                </div>
                  <br>
                 
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("hr/hr-master/pegawai")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div>
		
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
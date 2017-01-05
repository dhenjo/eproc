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
                    array("id_detail" => $detail[0]->id_hr_company))?>
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
                  <label>telp</label>
                  <?php print $this->form_eksternal->form_input('telp', $detail[0]->telp, 'class="form-control input-sm" placeholder="telp"');?>
                </div>
                
                <div class="control-group">
                  <label>Fax</label>
                  <?php print $this->form_eksternal->form_input('fax', $detail[0]->fax, 'class="form-control input-sm" placeholder="Fax"');?>
                </div>
                
                <div class="control-group">
                  <label>Office</label>
                  <?php print $this->form_eksternal->form_input('office', $detail[0]->office, 'class="form-control input-sm" placeholder="Office"');?>
                </div>
               
                <div class="control-group">
                  <label>NPWP</label>
                  <?php print $this->form_eksternal->form_input('npwp', $detail[0]->npwp, 'class="form-control input-sm" placeholder="npwp"');?>
                </div>
                <div class="control-group">
                  <label>Address</label>
                  <?php print $this->form_eksternal->form_textarea('address', $detail[0]->address, 'class="form-control input-sm" id="Address"')?>
             
                </div>
                    
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("hr/hr-master/company")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

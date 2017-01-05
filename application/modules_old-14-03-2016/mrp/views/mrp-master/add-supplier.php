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
              print $this->form_eksternal->form_open("", 'role="form"', array("id_detail" => $detail[0]->id_mrp_supplier));
//            }
            ?>
              <div class="box-body">

                <div class="control-group">
                  <label>Supplier Name</label>
                  <?php print $this->form_eksternal->form_input('name', $detail[0]->name, 'class="form-control input-sm" placeholder="Supplier Name"');?>
                </div>
                
                <div class="control-group">
                  <label>PIC</label>
                  <?php print $this->form_eksternal->form_input('pic', $detail[0]->pic, 'class="form-control input-sm" placeholder="PIC"');?>
                </div>
                
                <div class="control-group">
                  <label>Phone</label>
                  <?php print $this->form_eksternal->form_input('phone', $detail[0]->phone, 'class="form-control input-sm" placeholder="Phone"');?>
                </div>
                  
                <div class="control-group">
                  <label>Fax</label>
                  <?php print $this->form_eksternal->form_input('fax', $detail[0]->fax, 'class="form-control input-sm" placeholder="fax"');?>
                </div>
                
                <div class="control-group">
                  <label>Email</label>
                  <?php print $this->form_eksternal->form_input('email', $detail[0]->email, 'class="form-control input-sm" placeholder="Email"');?>
                </div>
                
                <div class="control-group">
                  <label>Website</label>
                  <?php print $this->form_eksternal->form_input('website', $detail[0]->website, 'class="form-control input-sm" placeholder="website"');?>
                </div>
                
                <div class="control-group">
                  <label>Address</label>
                  <?php print $this->form_eksternal->form_input('address', $detail[0]->address, 'class="form-control input-sm" placeholder="address"');?>
                </div>
                
              </div>
              <div class="box-footer">
                 
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  
                  <a href="<?php print site_url("mrp/mrp-master/supplier")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

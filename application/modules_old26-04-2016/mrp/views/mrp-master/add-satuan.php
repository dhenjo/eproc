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
              print $this->form_eksternal->form_open("", 'role="form"', array("id_detail" => $detail[0]->id_mrp_satuan));
//            }
            ?>
              <div class="box-body">

                <div class="control-group">
                  <label>title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'class="form-control input-sm" placeholder="Title"');?>
                </div>
                <div class="control-group">
                  <label>Type</label>
                 <?php print $this->form_eksternal->form_dropdown('type', $type, $detail[0]->type, 'class="form-control" placeholder="Type"')?>
                </div>
                <div class="control-group">
                  <label>Nilai</label>
                  <?php print $this->form_eksternal->form_input('nilai', $detail[0]->nilai, 'class="form-control input-sm" placeholder="Nilai"');?>
                </div>
                  
                <div class="form-group">
                    <label class="control-label">Note</label>
                      <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, ' class="form-control input-sm"')?>
                </div>
               
              </div>
              <div class="box-footer">
                 
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  
                  <a href="<?php print site_url("mrp/mrp-master/satuan")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

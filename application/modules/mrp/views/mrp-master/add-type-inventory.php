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
                    array("id_detail" => $detail[0]->id_mrp_type_inventory))?>
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
                  <label>ISO</label>
                  <?php print $this->form_eksternal->form_input('iso', $detail[0]->iso, 'class="form-control input-sm" placeholder="iso"');?>
                </div>
                   <div class="control-group">
                    <h4>Status</h4>
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
                  <a href="<?php print site_url("mrp/mrp-master/type-inventory")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

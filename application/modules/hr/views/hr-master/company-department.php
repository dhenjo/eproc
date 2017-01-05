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
                  <label>Department</label>
                  <?php print $hasil;?>
                  <div class="input-group margin" id="department-box">
                      <input type="text" class="form-control" id="department" name="department[]">
                      <input type="text" class="form-control" id="id_department" name="id_department[]" style="display: none">
                      <span class="input-group-btn">
                          <a href="javascript:void(0)" class="btn btn-danger btn-flat delete" isi="department-box" >
                            <i class="fa fa-fw fa-times"></i>
                          </a>
                      </span>
                  </div>
                  <div id="wadah"></div>
                </div>
                <div class="control-group">
                  <br />
                  <a href="javascript:void(0)" id="add-row" class="btn btn-success btn-sm"><i class="fa fa-fw fa-plus"></i></a>
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
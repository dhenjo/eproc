<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Pencarian</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
          <?php print $this->form_eksternal->form_open("", 'role="form"', array())?>
          <div class="row">
            <div class="form-group">
              <div class="col-xs-12">
                <label>title</label>
                 <?php print $this->form_eksternal->form_input('satuan_search_title', $this->session->userdata("satuan_search_title"),
                  'class="form-control input-sm" placeholder="Title"')?>
              </div>
             
            </div>
            
          </div>
            <div class="row">
            <div class="form-group">
                 <div class="col-xs-6">
                <label>Type</label>
                <?php print $this->form_eksternal->form_dropdown('satuan_search_type', $type, $this->session->userdata("satuan_search_type"), 'class="form-control" placeholder="Type"')?>
              </div>
              <div class="col-xs-6">
                <label>Nilai</label>
                 <?php print $this->form_eksternal->form_input('satuan_search_nilai', $this->session->userdata("satuan_search_nilai"),
                  'class="form-control input-sm" placeholder="Nilai"')?>
              </div>
            </div>
            
          </div>
          <div class="row">
            <div class="col-xs-3">
              <br />
              <button class="btn btn-primary" type="submit">Search</button>
              <hr />
            </div>
          </div>
          </form> 
        </div>
    </div>
  </div>
</div>
<?php // print $parent."aa"; die; ?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Satuan List</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <?php
          
            if($group == "" AND $sort == ""){ ?>
                <a href="<?php print site_url("mrp/mrp-master/add-satuan")?>" class="btn btn-success btn-sm"><i class="fa  fa-plus"></i></a>
            <?php }else{
                $dt_sort = $this->global_models->get_field("mrp_satuan", "max(sort)",array("group_satuan" => "{$group}","status" => 1));
//                print $dt_sort."aa";
                if($sort >= $dt_sort){ ?>
                    <a href="<?php print site_url("mrp/mrp-master/add-satuan/{$group}/{$sort}")?>" class="btn btn-success btn-sm"><i class="fa  fa-plus"></i></a>
                <?php }else{ ?>
                    
            <?php } } ?>   
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Nilai</th>
                        <th>Parent</th>
                        <th>Note</th>
                        <th style="width: 120px;">Action</th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
<!--                  <tfoot>
                    <tr>
                      <td colspan="3">
                        <button type='button' class='btn btn-warning btn-flat'>Set Draft</button>
                        <button type='button' class='btn btn-danger btn-flat'>Delete</button>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      
                    </tr>
                  </tfoot>-->
                </table>
              </div>
            </div>
          </div> 
        </div>
    </div>
  </div>
</div>
<div id="script-tambahan">
  
</div>
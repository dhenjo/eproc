
<div class="row">
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Supplier</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
            </div>
          
        </div>
            <div class="box-body">
             <div class="control-group">
               <label>Supplier</label>
               <?php 
                print $this->form_eksternal->form_input("supplier", $nama_supplier, 'id="supplier" class="form-control input-sm" placeholder="Supplier"');
                  print $this->form_eksternal->form_input("id_supplier", $id_mrp_supplier, 'id="id_supplier" style="display: none"');
                  
                  ?>
             </div>
              </div>
    </div>
  </div>
    
    <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Perusahaan</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
            </div>
          
        </div>
            <div class="box-body">
             <div class="control-group">
               <label>Perusahaan</label>
               <?php 
                print $this->form_eksternal->form_input("company", $nama_perusahaan, 'id="company" class="form-control input-sm" placeholder="Perusahaan"');
                print $this->form_eksternal->form_input("id_company", $id_perusahaan, 'id="id_company" style="display: none"');
                  
                  ?>
             </div>
              </div>
    </div>
  </div>
    
     <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title"></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
            </div>
          
        </div>
<div class="box-body">
                <table class="table table-striped">
            <tr>
              <th>Discount</th>
              <td>
               <?php 
                print $this->form_eksternal->form_input("discount", "", 'onkeyup="FormatCurrency(this)" id="dt_discount" class="form-control input-sm" placeholder="Discount"');
                 
                  ?>
              </td>
            </tr>
            <tr>
              <th>PPN</th>
              <td>
               <?php 
                $array_cek = array(0 => "Tidak",
                    1 => "Ya");
                print $this->form_eksternal->form_dropdown("ppn", $array_cek, 
                    "", 'id="ppn" class="form-control dropdown2 input-sm"');?>
               
              </td>
            </tr>
             <tr>
              <th>Desimal</th>
              <td>
                 <?php 
//                $array_cek = array(0 => "Tidak",
//                    1 => "Ya");
                print $this->form_eksternal->form_dropdown("desimal", $array_cek, 
                    "", 'id="desimal" class="form-control dropdown2 input-sm"');?>
               
              </td>
            </tr>
                </table>
                
 </div>
    </div>
  </div>
     
    <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">List Task Request Orders</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <div class="widget-control pull-left">
              <span style="display: none; margin-left: 10px;" id="loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
            </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="box">
              <div class="box-body table-responsive">
                <table class="table table-bordered table-hover" id="tableboxy2">
                  <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th style="width: 70px;">Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th style="width: 180px;">Note</th>
                        <th>Keterangan</th>
                        <th></th>
                    </tr>
                  </thead>
                  <tbody id="data_list">
                    
                  </tbody>
                  <tfoot>
                       <tr>
                      <td colspan="3">
                          
<!--                        <button type='button' class='btn btn-warning btn-flat' id="btn-proses">Proses Pengajuan PO</button>
                        <button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>-->
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td><b>Sub Total</b></td>
                      <td><span id="dt-sub-total"></span></td>
                       <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3">
<!--                        <button type='button' class='btn btn-warning btn-flat' id="btn-proses">Proses Pengajuan PO</button>
                        <button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>-->
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td><b>Discount</b></td>
                      <td><span id="total-disc">0</span></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3">
<!--                        <button type='button' class='btn btn-warning btn-flat' id="btn-proses">Proses Pengajuan PO</button>
                        <button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>-->
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td><b>PPN</b></td>
                      <td><span id="total-ppn">0</span></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3">
<!--                        <button type='button' class='btn btn-warning btn-flat' id="btn-proses">Proses Pengajuan PO</button>
                        <button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>-->
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td><b>Total</b></td>
                      <td><span id="dt-total">0</span></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                     <tr>
                      <td colspan="4">
                      <?php
                    
                      if($total > 0){?> 
                      <div id="tombol"> 
                        <button type='button' class='btn btn-warning btn-flat' id="btn-proses">Proses Pengajuan PO</button>
                        <button type='button' class='btn btn-warning btn-flat' id="btn-draft">Set Draft</button>
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                        </div>
                      <?php } ?>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </tfoot>
                  
                </table>
              </div>
            </div>
          </div> 
        </div>
        <div class="overlay" id="load-data1" style="display: none"></div>
        <div class="loading-img" id="load-data2" style="display: none"></div>
    </div>
       
       
  </div>
<!--    <div class="col-xs-12">
    <div class="box box-solid box-info">
        <div class="box-header">
            <h3 class="box-title">Button</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
            </div>
        </div>
   
        <div class="box-footer">
                 
                  <button class="btn btn-primary" type="submit" id="btn-task-orders">Save changes</button>
                   <div class="widget-control pull-left">
                    <span style="display: none; margin-left: 10px;" id="btn-loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                  </div>
                  <a href="<?php print site_url("mrp/mrp-master/brand")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
    </div>
  </div>-->

</div>
<div id="script-tambahan">
  
</div>
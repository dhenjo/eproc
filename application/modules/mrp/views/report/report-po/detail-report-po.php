<div class="row">
   
    <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title"></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
            </div>
          
        </div>
             <div class="box-body">
        <?php print $this->form_eksternal->form_open("", 'role="form"', 
          array("id_detail" => ""))?>
          <table class="table table-striped">
             
             <tr>
              <th>No. PO</th>
              <td><div id="no_po"><?php print $list[0]->no_po; ?></div></td>
            </tr>
           <?php
            $tgl_po = "";
            if($list[0]->tanggal_po != "0000-00-00" AND $list[0]->tanggal_po != ""){
            $tgl_po = date("d/m/y", strtotime($list[0]->tanggal_po));
          
            }
          ?>
            <tr>
              <th style="width: 27%;">Tanggal PO</th>
              <td>
                   <?php print $tgl_po; ?>
              </td>
            </tr>
            <?php
            $tgl_surat_jln = "";
            if($surat_jalan != "0000-00-00" AND $surat_jalan != ""){
            $tgl_surat_jln = date("d/m/y", strtotime($surat_jalan));
          
            }
            
            ?>
            <tr>
              <th style="width: 27%;">Surat Jalan</th>
              <td>
                   <?php print $tgl_surat_jln; ?>
              </td>
            </tr>
            
            <tr>
              <th style="width: 27%;">Lama</th>
              <td>
                   <?php print $lama; ?>
              </td>
            </tr>
            
             <tr>
              <th style="width: 27%;">Supplier</th>
              <td>
                   <?php print $list[0]->supplier; ?>
              </td>
            </tr>
            
            <tr>
              <th style="width: 27%;">Beban</th>
              <td>
                   <?php print $beban; ?>
              </td>
            </tr>
          </table> 
              </form>    
        </div>
    </div>
  </div>
     
    <div class="col-xs-12">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">List Purchase Orders</h3>
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
                <table class="table table-bordered table-hover" id="tableboxy">
                  <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th style="width: 30px;">Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th style="width: 150px;">Note</th>
                        <th>Keterangan</th>
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
                    </tr>
                      <tr>
                      <td colspan="3">
<!--                        <button type='button' class='btn btn-warning btn-flat' id="btn-proses">Proses Pengajuan PO</button>
                        <button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>-->
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td><b>Discount</b></td>
                      <td><span id="dt-discount"></span></td>
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
                      <td><span id="dt-ppn"></span></td>
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
                      <td><span id="dt-total"></span></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                                     </tfoot>
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


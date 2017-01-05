<div class="row">
    <div style="display:none" id="dt_discount"><?php print number_format($list[0]->discount); ?></div>
    <div style="display:none" id="ppn"><?php print $list[0]->ppn; ?></div>
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
          array("id_detail" => $id_mrp_po))?>
          <table class="table table-striped">
             
            <?php if($list[0]->no_po){ ?>  
             <tr>
              <th>No. PO</th>
              <td><div id="no_po"><?php print $list[0]->no_po; ?></div></td>
            </tr>
            <?php } ?>
            <?php
          
            if($list[0]->tanggal_po == "0000-00-00" AND $list[0]->tanggal_po == ""){
            $tgl_po = "";
            }else{
              $tgl_po = $list[0]->tanggal_po;
          } ?>
            <tr>
              <th style="width: 27%;">Tanggal PO</th>
              <td>
                 <?php print $tgl_po; ?>
              </td>
            </tr>
            <?php
            if($list[0]->code){
            ?>
             
             <tr>
              <th>Kode PO</th>
              <td style="width: 75%"><?php print $list[0]->code; ?></td>
            </tr>
            
            <tr>
              <th>Discount</th>
              <td style="width: 75%"><?php print number_format($list[0]->discount); ?></td>
            </tr>
            <?php 
            $array_cek = array(0 => "Tidak",
                    1 => "Ya");
            if($list[0]->ppn){
                $data_ppn = $list[0]->ppn;
            }else{
                $data_ppn = 0;
            }
            ?>
            <tr>
              <th>PPN</th>
              <td style="width: 75%"><?php print $array_cek[$data_ppn]; ?></td>
            </tr>
            <tr>
              <th>Desimal</th>
              <td style="width: 75%"><?php print $array_cek[$list[0]->flag_desimal]; ?></td>
            </tr>
            <?php } ?>
            <?php
           
           $date_receive= explode(",",$list[0]->date_receive);
         
            if($date_receive[0] != "0000-00-00" AND $date_receive[0] != ""){
                $tgl = date("d M Y", strtotime($date_receive[0]));
            }else{
                $tgl = "";
            }
          
            if($list[0]->tanggal_payment != "0000-00-00" AND $list[0]->tanggal_payment != ""){
                $tgl_payment = date("d M Y", strtotime($list[0]->tanggal_payment));
            }
          
            ?>
            <?php if($list[0]->status >= 5 AND $list[0]->status <= 7){
            ?>
            <tr>
              <th>Approval</th>
              <td><?php print $this->global_models->get_field("m_users", "name", array("id_users" => $list[0]->user_approval)); ?></td>
            </tr>
            <tr>
              <th style="width: 27%;">Tanggal Diterima Barang</th>
              <td>
                  <?php print $tgl; ?>
                
              </td>
            </tr>
            <tr>
              <th>Note PO</th>
              <td>
                  <?php  print nl2br($list[0]->note); ?>
              </td>
            </tr>
            <?php } ?>
            <?php
            if($list[0]->status != 8){
            if($list[0]->status >= 4 AND $list[0]->status <= 7){?>
    
            
            <?php }
             } ?>
            <tr>
              <th>Status</th>
              <td><?php print $dt_status[$list[0]->status]; ?></td>
            </tr>
           
            <tr>
              <th>Checker</th>
              <td><?php print $list[0]->name_checker; ?></td>
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

                      </td>
                      <td><b>Sub Total</b></td>
                      <td><span id="dt-sub-total"><?php print $list[0]->total; ?></span></td>
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
                      <td>
                      </td>
                      
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


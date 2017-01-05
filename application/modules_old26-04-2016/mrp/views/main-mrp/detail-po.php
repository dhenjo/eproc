
<div class="row">
    
    <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Perusahaan</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
             <div class="box-body">
          <table class="table table-striped">
            <tr>
              <th style="width: 27%;">Nama Perusahaan</th>
              <td><?php print $list[0]->nama_perusahaan; ?></td>
            </tr>
            <tr>
              <th>Kantor</th>
              <td><?php print $list[0]->office; ?></td>
            </tr>
            <tr>
              <th>Alamat</th>
              <td><?php print nl2br($list[0]->alamat_perusahaan); ?></td>
            </tr>
          </table>
        </div>
    </div>
  </div>
    
  <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">Supplier</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
            </div>
          
        </div>
             <div class="box-body">
          <table class="table table-striped">
            <tr>
              <th>Nama Supplier</th>
              <td><?php print $list[0]->name; ?></td>
            </tr>
             <tr>
              <th>PIC</th>
              <td><?php print $list[0]->pic; ?></td>
            </tr>
            <tr>
              <th>No. Telp</th>
              <td><?php print $list[0]->phone; ?></td>
            </tr>
            <tr>
              <th>Fax</th>
              <td><?php print $list[0]->fax; ?></td>
            </tr>
            <tr>
              <th>Alamat</th>
              <td><?php print nl2br($list[0]->address); ?></td>
            </tr>
          </table>
        </div>
    </div>
  </div>
    
    <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title"></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
            </div>
          
        </div>
             <div class="box-body">
          <table class="table table-striped">
            <?php if($list[0]->no_po){ ?>  
             <tr>
              <th>No. PO</th>
              <td><?php print $list[0]->no_po; ?></td>
            </tr>
            <?php } 
            if($list[0]->code){
            ?>
             <tr>
                 <th >Kode PO</th>
              <td style="width: 75%"><?php print $list[0]->code; ?></td>
            </tr>
            <?php } ?>
            <?php
            if($detail[0]->tanggal_dikirim != "0000-00-00" AND $detail[0]->tanggal_dikirim != ""){
            $tgl = "pada tanggal ".date("d M Y", strtotime($detail[0]->tanggal_dikirim));
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
              <th style="width: 27%;">Tanggal Dikirim</th>
              <td><?php print $this->form_eksternal->form_input("tgl_dikirim", $tgl, ' id="tgl_dikirim" class="form-control date input-sm" placeholder="Tanggal Dikirim"'); ?></td>
            </tr>
            <tr>
              <th>Note PO</th>
              <td><?php print $this->form_eksternal->form_textarea('note', $list[0]->note, ' id="note_po" class="form-control input-sm"'); ?></td>
            </tr>
            <?php } ?>
            <tr>
              <th>Status</th>
              <td><?php print $dt_status[$list[0]->status]; ?></td>
            </tr>
            <?php if($list[0]->status >= 6){ ?>
            <tr>
              <th>Tanggal Pembayaran</th>
              <td><?php print $tgl_payment; ?></td>
            </tr>
            <tr>
              <th>Note Pembayaran</th>
              <td><?php print $list[0]->note_payment; ?></td>
            </tr>
            <?php
            $status_payment1 = array("" => "-","1" => "Lunas","2" => "Belum Lunas");
            ?>
            <tr>
              <th>Status Pembayaran</th>
              <td><?php print $status_payment1[$list[0]->status_payment]; ?></td>
            </tr>
            <?php } ?>
          </table>
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
                      <td><b>Total</b></td>
                      <td><span id="dt-total"></span></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3">
                    <?php if($list[0]->status == 4){?> 
                      <?php if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-po", "edit") !== FALSE){ ?>
                        <button type='button' class='btn btn-warning btn-flat' id="btn-approval">Approval PO</button>
                        <button type='button' class='btn btn-danger btn-flat' id="btn-revisi">Revisi PO</button>
                      <?php } ?>
                    <?php }elseif($list[0]->status == 5){ ?>
                        <button type='button' class='btn btn-warning btn-flat' id="btn-send">Send PO</button>
                    <?php } ?>
                        <!--<button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>-->
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>
                          <?php if($list[0]->status >= 5 AND $list[0]->status <= 7){ ?>
                          <a href="<?php print site_url("mrp/preview/{$id_mrp_task_orders}/{$id_mrp_po}"); ?>" onclick="window.open(this.href,'_blank'); return false;" class="btn bg-maroon margin" >Print Preview</a>
                          <a href="<?php print site_url("mrp/po_pdf/{$id_mrp_task_orders}/{$id_mrp_po}"); ?>" onclick="window.open(this.href,'_blank'); return false;" class="btn bg-maroon margin" >PDF</a>
                          <!--<button type='button' class='btn bg-maroon margin btn-flat' id="btn-approval">Print Preview</button>-->
                          <?php } ?>
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
    
    <?php
    if($list[0]->status == 6){?>
    <div class="col-xs-12">
    <div class="box box-solid box-info">
        <div class="box-header">
            <h3 class="box-title">Button</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
            </div>
        </div>
   
        <div class="box-footer">
                 
                  <button class="btn btn-primary" type="submit" id="btn-closed-po">Closed PO</button>
                   <div class="widget-control pull-left">
                    <span style="display: none; margin-left: 10px;" id="btn-loader-page"><img width="35px" src="<?php print $url?>img/ajax-loader.gif" /></span>
                  </div>
                  <!--<a href="<?php print site_url("mrp/rg/{$id_mrp_po}"); ?>" onclick="window.open(this.href,'_blank'); return false;" class="btn bg-maroon margin" >Receiving Goods</a>-->
              </div>
    </div>
  </div>
    <?php } ?>
</div>
<div id="script-tambahan">
  
</div>


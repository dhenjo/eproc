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
                  <?php if($list[0]->status != 12 AND $list[0]->status != 7){?>
                  <?php print $this->form_eksternal->form_input("tgl_po", $tgl_po, ' id="tgl_po" class="form-control date input-sm" placeholder="Tanggal PO"'); ?>
                  <?php }else{?>
                   <?php print $tgl_po; ?>
                  <?php } ?>
              </td>
            </tr>
            <?php
            if($list[0]->code){
            ?>
             <tr>
              <th></th>
              <td style="width: 75%">
                    <?php if($list[0]->status != 12 AND $list[0]->status != 7){?>
                  <?php print $this->form_eksternal->form_input("frm", $frm, ' id="id_frm" class="form-control input-sm" placeholder=""'); ?></td>
                  <?php }else{?>
                    <?php print $frm; ?>
                  <?php }?>
             </tr>
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
                 <?php if($list[0]->status != 12 AND $list[0]->status != 7){?>
                  <?php print "<a href='".site_url("mrp/rg/{$date_receive[1]}")."'  title='RG' style='width: 40px'>{$tgl}</a>"; ?>
                  <?php }else{
                      print  "<a href='".site_url("mrp/rg/{$date_receive[1]}")."'  title='RG' style='width: 40px'>{$tgl}</a>";
                  } ?>
              </td>
            </tr>
            <tr>
              <th>Note PO</th>
              <td>
                   <?php if($list[0]->status != 12 AND $list[0]->status != 7){?>
                  <?php print $this->form_eksternal->form_textarea('note', $list[0]->note, ' id="note_po" class="form-control input-sm"'); ?>
                   <?php }else{
                       print nl2br($list[0]->note);
                   }?>
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
            <?php if($list[0]->status >= 5){ ?>
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
            
            <tr>
              <th>Checker</th>
              <?php if($list[0]->user_checker OR $list[0]->status > 4){ ?>
              <td><?php print $list[0]->name_checker; ?></td>
              <?php }else{
       if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "checker-po", "edit") !== FALSE){      
             print "<td>".$this->form_eksternal->form_checkbox('user_checker', 1, FALSE,"style='box-shadow: 0px 2px 5px 0px'")."</td>";
       }else{
           print "<td>Butuh checker sebelum melakukan Approval PO</td>";
       }     
                }?>
             
            </tr>
            
          
            <tr>
                <?php if($list[0]->status == 4 AND $list[0]->user_checker == "" OR $list[0]->user_checker == 0){ ?>
                <th><button class="btn btn-primary btn-flat" type="submit">Checker</button></th>
                <?php }else{?><th></th><?php } ?>
                <td>
                    <?php if($list[0]->status < 9 AND $list[0]->status != 7){?>
                    <button class="btn btn-danger btn-flat" name="btn_cancel" value="cancel_po" type="submit">Cancel PO</button>
                    <?php } ?>
                </td>
                
            </tr>
           <tr>
                <th> <input type='submit' name='export' value='Export Excel' class='btn btn-success' id="export-excel"></input></th>
                <td>
                   
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
                    <tr>
                      <td colspan="4">
                    <?php if($list[0]->status == 4){?> 
                      <?php if($this->session->userdata("id") == 1 OR $this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval-po", "edit") !== FALSE){ ?>
                         <?php if($list[0]->user_checker !=0 ){?>
                        <button type='button' class='btn btn-warning btn-flat' id="btn-approval">Approval PO</button>
                         <?php }
                         ?>
                       <button type='button' class='btn btn-danger btn-flat' id="btn-revisi">Revisi PO</button>
                      <?php }?>
                    <?php }elseif($list[0]->status == 5){ 
                        print "Blast Email: ".$this->form_eksternal->form_checkbox('blast_email', 1, TRUE," id='blast' style='box-shadow: 0px 2px 5px 0px'");
                        ?>
                       <div style="padding-left: 40%;margin-top: -5%;">
                        <button type='button' class='btn btn-warning btn-flat' id="btn-send">Send PO</button>
                       </div>
                    <?php } ?>
                        <!--<button type='button' class='btn btn-info btn-flat' id="btn-save">Save</button>-->
                        <!--<button type='button' class='btn btn-danger btn-flat'>Delete</button>-->
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>
                          <?php if($list[0]->status >= 5 AND $list[0]->status <= 7){ ?>
                          <a href="<?php print site_url("mrp/preview/{$id_mrp_task_orders}/{$id_mrp_po}"); ?>" onclick="window.open(this.href,'_blank'); return false;" class="btn bg-maroon margin" >Print Preview</a>
                          <!--<a href="<?php print site_url("mrp/po_pdf/{$id_mrp_task_orders}/{$id_mrp_po}"); ?>" onclick="window.open(this.href,'_blank'); return false;" class="btn bg-maroon margin" >PDF</a>-->
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
                  <?php if(empty($id_blast_email_po)){ ?>
                  <?php print $this->form_eksternal->form_open("", 'role="form"', 
          array("id_detail" => $id_mrp_po))?>
                  <div style="padding-left: 80%;margin-top: -3% ">
                      <input type="submit" name="b_email" class="btn btn-success" type="submit" value="Blast Email" id="btn-blast-email">
                          
                      </input>
                 </form>
                  </div>
                   <?php } ?>
                  
                  <!--<a href="<?php print site_url("mrp/rg/{$id_mrp_po}"); ?>" onclick="window.open(this.href,'_blank'); return false;" class="btn bg-maroon margin" >Receiving Goods</a>-->
         </div>
    </div>
  </div>
    <?php } ?>
</div>
<div id="script-tambahan">
  
</div>


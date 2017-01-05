<?php
$status = array( 1=> "<span class='label bg-orange'>Draft</span>", 2 => "<span class='label bg-blue'>Pengajuan</span>",
        3 => "<span class='label bg-green'>Approved</span>",4 => "<span class='label bg-green'>Task Order</span>",
        5 => "<span class='label bg-green'>Proses PO</span>", 6 => "<span class='label bg-green'>Sent PO</span>",
        7 => "<span class='label bg-green'>Partial</span>",8 => "<span class='label bg-green'>RG</span>",
        9 => "<span class='label bg-green'>Closed RO</span>", 10 => "<span class='label bg-red'>CANCEL RO</span>",
        11=> "<span class='label bg-black'>Reject RO</span>");
?>
<div class="row">
    
    <div class="col-xs-12">
    <div class="box box-solid box-success">
        <div class="box-header">
            <h3 class="box-title">DETAIL</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
             <div class="box-body">
          <table class="table table-striped">
            <tr>
              <th style="width: 27%;">Kode Request</th>
              <td><?php print $list[0]->code; ?></td>
            </tr>
            <tr>
              <th>Users Request</th>
              <td><?php print $list[0]->name; ?></td>
            </tr>
            <tr>
              <th>Penerima Barang dari suplier</th>
              <td><?php print $list[0]->name_receiver; ?></td>
            </tr>
            <tr>
              <th>Created By Users</th>
              <td><?php print $created_by_users; ?></td>
            </tr>
            <tr>
              <th>Status</th>
              <td><?php print $status[$list[0]->status]; ?></td>
            </tr>
          </table>
        </div>
    </div>
  </div>

</div>
<div id="script-tambahan">
  
</div>


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
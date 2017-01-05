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
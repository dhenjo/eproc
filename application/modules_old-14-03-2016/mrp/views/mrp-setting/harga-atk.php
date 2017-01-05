<thead>
    <tr>
        <th>Tanggal Update</th>
        <th>Supplier</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    foreach ($data as $key => $value) {
        
      print '
      <tr>
        <td>'.date('d-M-Y H:i:s', strtotime($value->update_date)).'</td>
        <td>'.$value->name.'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("mrp/mrp-setting/add-harga-atk/".$value->id_mrp_setting_harga_atk).'">Edit</a></li>
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
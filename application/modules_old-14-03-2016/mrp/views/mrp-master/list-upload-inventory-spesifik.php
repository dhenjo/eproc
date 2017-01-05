<?php

//print_r($data); die;
?>
<thead>
    <tr>
        <th>Inventory</th>
        <th>File</th>
        <th>Option</th>
    </tr>
</thead>
<tbody>
  <?php
  $kategori_barang = array( 1 => "ATK",
                            2 => "Cetakan");
  if(is_array($data)){
     foreach ($data as $key => $value) {
      
  $data_file .= '<tr>
        <td>Inventory Spesifik</td>
        <td>'.$value->file.'</td><td>';
  if($value->status == 1){
      $data_file .= '<div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("mrp/mrp-master/proses-import/{$value->id_import_mrp_inventory}/2").'">Proses</a></li>
             <!-- <li><a href="'.site_url("master/supplier/delete-product-tour/".$value->id_import_product).'">Delete</a></li> -->
            </ul>
          </div>'; }
          elseif($value->status == 2){
          $data_file .=  "File sudah di Import";
          }
       $data_file .=  '</td>
      </tr>';
    }
    print $data_file;
  }
  ?>
</tbody>
<?php

//print "<pre>";
//print_r($data); 
//print "</pre>";
//die; 

print $before_table;
?>


<thead>
    <tr>
      <th>Tanggal</th>
        <th>Name TC</th>
        <th>Konfirmasi Oleh</th>
        <th>Book Code</th>
        <th>Name</th>
        <th>Status</th>
        <th>Payment Type</th>
        <th>Currency</th>
        <th>Nominal IDR</th>
    </tr>
</thead>
<tbody>
  <?php
  
    foreach ($data as $key => $value) {
     
      if($value['currency'] == "IDR"){
        $nom_idr = $value['nominal'];
        $nom_idr_tot += $value['nominal'];
      }elseif($value['currency'] == "USD"){
        $nom_usd = $value['nominal'];
        $nom_usd_tot += $value['nominal'];
      }
        
      print '
      <tr>
        <td>'.date("Y-m-d H:i:s", strtotime($value['tanggal'])).'</td>
        <td>'.$value['name_tc'].'</td>
          <td>'.$value['name_konfirm'].'</td>
          <td>'.$value['book_code'].'</td>
        <td>'.$value['name'].'</td>
        <td>'.$status[$value['status']].'</td>
        <td>'.$channel[$value['payment_type']].'</td>
        <td>'.$value['currency'].'</td>
        <td>'.number_format($nom_idr,0,".",",").'</td>
         </tr>';
//      $total['harga_tiket'] += $value->harga_bayar + $value->diskon;
//      $total['diskon'] += $value->diskon;
//      $total['uang_terima'] += ($value->harga_bayar);
//      $r++;
    }

  ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="8" style="text-align: center"><b>TOTAL</b></td>
       
        <td style="text-align: right"><b><?php print number_format($nom_idr_tot,0,".",",")?></b></td>
    </tr>
</tfoot>

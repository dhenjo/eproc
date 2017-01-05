<style>
  @media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}
  body{
/*    font-size: smaller;*/
  }
  table{
    font-size: smaller;
  }
</style>

<?php
//print "<pre>";
//print_r($list);
//print "</pre>";
if($detail[0]->tanggal_dikirim != "0000-00-00" AND $detail[0]->tanggal_dikirim != ""){
  $tgl = "pada tanggal ".date("d M Y", strtotime($detail[0]->tanggal_dikirim));
}
?>
<body onload="window.print();">
<!--<body>-->
  <table width="100%" style="padding-bottom: 3%;border: 2">
    <tr>
      <td width="100%"><img src="<?php print base_url()."files/images/logo.png"?>" /><div style="text-align: center;margin-top:-60px"><u><span style="font-size:17px;">ORDER PEMBELIAN</span><br><span style="font-size:12px;padding-left: 5px"><?php print $detail[0]->no_po; ?></span></u></div></td>
    </tr>
    <tr>
      <td width="100%"><div style="font-size: 14px; margin-top: 30px"><?php print $detail[0]->office." - ".$detail[0]->company."<br>".$detail[0]->address_company; ?><br><br>
          </div>
        <div style="text-align: center;padding-left: 430px;margin-top: -80px;"><span style="font-size: 12px;">Kepada Yth,<br></span><span style="font-size: 14px;"><?php print $detail[0]->nama_supplier."<br>".$detail[0]->pic."/".$detail[0]->phone."<br>".$detail[0]->address_supplier; ?></span></div></td>
    </tr>
    <tr>
      <td width="100%"><div style="font-size: 14px; margin-top: 1%">Dengan Hormat,
  <br>Mohon dapat dikirimkan kepada kami <?php  print $tgl;?> barang-barang sbb :</div>
        </td>
    </tr>
  </table>
  <table width="100%" style="border-collapse: collapse;border: 1px solid black;">
    <tr>
      <th style="border: 2px solid black;width: 20px;">No</th>
      <th style="border: 2px solid black;">Jenis Barang</th>
      <th style="border: 2px solid black;">Jumlah<br>Barang</th>
      <th style="border: 2px solid black;">Satuan</th>
      <th style="border: 2px solid black;">Harga<br>Satuan</th>
      <th style="border: 2px solid black;">Total<br>Harga</th>
      <th style="border: 2px solid black;">Keterangan</th>
    </tr>
    <?php
    $no =0;
    foreach($list AS $py){
        if($py->title_spesifik){
            $title_spesifik = " ".$py->title_spesifik;
        }else{
            $title_spesifik = "";
        }
      $no = $no + 1;
//      $debit = ($py->pos == 1 ? $py->nominal : 0);
//      $kredit = ($py->pos == 2 ? $py->nominal : 0);
      $total = number_format(($py->jumlah * $py->nilai ) * $py->harga_task_order_request);
      $total2 += (($py->jumlah * $py->nilai) * $py->harga_task_order_request);
      $note2 = nl2br($py->note);
      
      $nama_barang = $py->nama_barang.$title_spesifik;
      print "<tr>"
        . "<td style='border: 2px solid black;text-align: center;'>{$no}</td>"
        . "<td style='border: 2px solid black;'>{$nama_barang}</td>"
        . "<td style='border: 2px solid black;'><center>{$py->jumlah}</center></td>"
        . "<td style='border: 2px solid black;'><center>{$py->satuan}</center></td>"
        . "<td style='border: 2px solid black;'><center>".number_format($py->harga_task_order_request)."</center></td>"
        . "<td style='border: 2px solid black;'><center>Rp {$total}</center></td>"
        . "<td style='border: 2px solid black;'>{$note2}</td>";
//        . "<td style='text-align: right; border-style:none none dotted none;'>".number_format($debit)."</td>"
//        . "<td style='text-align: right; border-style:none none dotted none;'>".number_format($kredit)."</td></tr>";
//      $t_debit += $debit;
//      $t_kredit += $kredit;
    }
    if($signature){
        $sgn = "src=".base_url()."files/antavaya/signature/{$signature}";
        $sgn2 ="<img style='width: 120px;height: 80px;' $sgn >";
    }else{
        $sgn2 = "";
    }
    ?>
    
    <tr>
      <td colspan="5" style="border: 2px solid black;"><center><b>Total</b></center></td>
      <td style="border: 2px solid black;"><center><b>Rp <?php print number_format($total2)?></b></center></td>
     <td style="border: 2px solid black;"></td>
    </tr>
  </table>
<p></p>
  <table width="100%">
    <tr>
      <td width="30%">Demikian dan terima kasih.<br><br><b>Jakarta, <?php print date("d F Y")?></b><br><br><span style="padding-left: 40px">Hormat Kami</span></td>
      <td>Keterangan :</td>
      <td><br><br><br><br><span style="padding-left: 10px">Diterima oleh</span></td>
    </tr>
    <tr>
      <td>
          <span style="padding-left: 15px"><?php print $sgn2; ?></span>
      </td>
      <td>- Tagihan harus disertai dengan ORDER pembelian asli.<br>- Tanda Terima KWITANSI dilaksanakan setiap hari SELASA
dan JUM'AT.<br>- Pembayaran dilakukan setiap hari SELASA dan JUM'AT.<br>- Barang yang dikirim dan tidak sesuai permintaan akan
ditolak max. 7 hari setelah tanggal pengiriman.<br></td>
      <td></td>
    </tr>
    <tr>
      <td><span style="padding-left: 40px">( <?php print $detail[0]->nama_user; ?> )</span></td>
      <td>&nbsp;</td>
      <td><span style="padding-left: 10px">(...............................)</span></td>
    </tr>
   <tr >
      <td width="20%">&nbsp;</td>
     <td width="60%"><div style="margin-top: 20%"><b><?php print $note; ?></b></div></td>
     <td width="20%">&nbsp;</td>
    </tr>
  </table>
</body>

<!--<hr />-->

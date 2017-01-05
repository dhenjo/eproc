<!--<style>
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
</style>-->

<style>
  @media print {

    * {
        color: #000 !important;
        -webkit-text-shadow: none !important;
        text-shadow: none !important;
        font-family: "Times New Roman", Times, serif;
        background: transparent !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        font-weight: normal!Important;
    }

    header, nav, footer {
       overflow:visible;
    }

    .body {
        width: auto;
       
        margin: 0 2%;
        padding: 0;
        float: none !important;
    }

    abbr[title]:after {
        content: " (" attr(title) ")";
    }

    pre,
    blockquote {
        border: 1px solid #999;
        page-break-inside: avoid;
    }

    thead {
        display: table-header-group;
    }

    tr,
    img {
        page-break-inside: avoid;
    }

    img {
        max-width: 100% !important;
    }

    @page {
        margin: 0.2cm;
    }

    .dtcontent {
        orphans: 3;
        widows: 3;
    }

    .dtcontent {
        page-break-after: avoid;
    }
}
 body{
/*    font-size: smaller;*/
  }
  table{
    font-size: 14px;
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
<header class="onlyprint">
  <table width="100%" style="padding-bottom: 3%;border: 2">
    <tr>
      <td width="100%"><img src="<?php print base_url()."files/images/logo.png"?>" /><div style="text-align: center;margin-top:-60px"><u><span style="font-size:17px;">FORM PERMINTAAN</span><br><span style="font-size:12px;padding-left: 5px"><?php print $detail[0]->no_po; ?></span></u></div></td>
    </tr>
     <tr>
      <td width="100%"><div style="font-size: 14px; margin-top: 30px"><?php // print $detail[0]->office." - ".$detail[0]->company."<br>".$detail[0]->address_company; ?><br><br>
          </div>
        <div style="text-align: center;padding-left: 430px;margin-top: -60px;"><span style="font-size: 12px;"><?php print $detail[0]->frm; ?></span><br><span style="font-size: 12px;">Kepada Yth,<br></span><span style="font-size: 12px;">PROCUREMENT</span></div></td>
    </tr>
    <tr>
      <td width="100%"><div style="font-size: 14px; margin-top: 4%">Dengan Hormat,
  <br>Mohon dapat dikirimkan kepada kami <?php  print $tgl;?> barang-barang sbb :</div>
        </td>
    </tr>
  </table>
  <table width="100%" style="border-collapse: collapse;border: 1px solid black;" class="dtcontent">
    <tr>
      <th style="border: 1px solid black;width: 20px;">No</th>
      <th style="border: 1px solid black;">Jenis Barang</th>
      <th style="border: 1px solid black;">Jumlah<br>Barang</th>
      <th style="border: 1px solid black;">Satuan</th>
      <th style="border: 1px solid black;">Harga<br>Satuan</th>
      <th style="border: 1px solid black;">Total<br>Harga</th>
      <th style="border: 1px solid black;">Keterangan</th>
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
//       $harga =$this->global_models->get_field("mrp_task_orders_request_asset", "harga", array("id_mrp_task_orders" => $py->id_mrp_task_orders, "id_mrp_inventory_spesifik" =>$py->id_mrp_inventory_spesifik));
   
    $total = number_format(($py->jumlah * $py->nilai ) * $py->harga);
      $total2 += (($py->jumlah * $py->nilai) * $py->harga);
      $note2 = nl2br($py->note);
      
       $brand = "";
    if($py->brand){
        $brand = ", Merk: ".$py->brand;
    }
      $nama_barang = $py->nama_barang.$title_spesifik.$brand;
      print "<tr>"
        . "<td style='border: 1px solid black;text-align: center;'>{$no}</td>"
        . "<td style='border: 1px solid black;'>{$nama_barang}</td>"
        . "<td style='border: 1px solid black;'><center>{$py->jumlah}</center></td>"
        . "<td style='border: 1px solid black;'><center>{$py->satuan}</center></td>"
        . "<td style='border: 1px solid black;'><center>".number_format($py->harga)."</center></td>"
        . "<td style='border: 1px solid black;'><center>Rp {$total}</center></td>"
        . "<td style='border: 1px solid black;'>{$note2}</td>";
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
      <td colspan="5" style="border: 1px solid black;"><center><b>Total</b></center></td>
      <td style="border: 1px solid black;"><center><b>Rp <?php print number_format($total2)?></b></center></td>
     <td style="border: 1px solid black;"></td>
    </tr>
  </table>
<p></p>
</header>
<?php
    if($detail[0]->tanggal_po != "0000-00-00" AND $detail[0]->tanggal_po != ""){
        $tgl_po = date("d M Y", strtotime($detail[0]->tanggal_po));
    }else{
        $tgl_po = date("d F Y");
    }
    ?>
<footer>
    
  <table width="100%">
    <tr>
      <td width="22%">Demikian dan terima kasih.<br><br><b>Jakarta, <?php print date("d F Y", strtotime($detail[0]->tanggal_po))?></b><br><br><span style="padding-left: 40px">Diminta oleh,</span></td>
      <td style="font-size:13px;padding-top: 47px;">Keterangan :</td>
      <td width="30%"><br><br><br><br>Mengetahui</td>
    </tr>
    <tr>
      <td>
          <span style="padding-left: 15px"><?php print $sgn2; ?></span>
      </td>
      <td style="font-size:13px;margin-bottom: 50%">- Permintaan paling lambat tanggal 3 setiap bulannya.<br></td>
      <td></td>
    </tr>
    <?php
    if($create_users == "26"){
        $name = "Implant";
		$approval = "Maria";
    }else{
        $name = $detail[0]->users;
		$approval = $detail[0]->nama_user_aproval;
    }
   
    ?>
   <tr>
      <td><span style="padding-left: 40px">(<?php print $name; ?>)</span></td>
      <td>&nbsp;</td>
      <td><span style="margin:0;">(<?php print $approval; ?>)</span></td>
    </tr>
   <tr >
      <td width="20%">&nbsp;</td>
     <td width="60%"><div style="margin-top: -2%"><b>Form ini di Approve by Sistem</b></div></td>
     <td width="20%">&nbsp;</td>
    </tr>
  </table>
  </footer>  
</body>

<!--<hr />-->

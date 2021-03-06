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
      <td width="100%"><img src="<?php print base_url()."files/images/logo.png"?>" /><div style="text-align: center;margin-top:-60px"><u><span style="font-size:17px;">ORDER PEMBELIAN</span><br><span style="font-size:12px;padding-left: 5px"><?php print $detail[0]->no_po; ?></span></u></div></td>
    </tr>
    <tr>
      <td width="100%"><div style="font-size: 14px; margin-top: 30px"><?php print $detail[0]->office." - ".$detail[0]->company."<br>".$detail[0]->address_company; ?><br><br>
          </div>
          <div style="text-align: center;padding-left: 430px;margin-top: -110px;"><span style="font-size: 12px;margin-left: 50%;"><?php print $detail[0]->frm; ?></span><br><br><br><span style="font-size: 12px;">Kepada Yth,<br></span><span style="font-size: 14px;"><?php print $detail[0]->nama_supplier."<br>".$detail[0]->pic."/".$detail[0]->phone."<br>".$detail[0]->address_supplier; ?></span></div></td>
    </tr>
    <tr>
      <td width="100%"><div style="font-size: 14px; margin-top: 1%">Dengan Hormat,
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
        
        if($py->catatan){
            $catatan = "<br>".nl2br($py->catatan);
        }else{
            $catatan = "";
        }
      $no = $no + 1;
//      $debit = ($py->pos == 1 ? $py->nominal : 0);
//      $kredit = ($py->pos == 2 ? $py->nominal : 0);
      $total = number_format(($py->jumlah * $py->nilai ) * $py->harga_task_order_request);
      $total2 += (($py->jumlah * $py->nilai) * $py->harga_task_order_request);
      $note2 = nl2br($py->note);
      
      if($detail[0]->flag_desimal == 1){
         $dt_jml = number_format($py->jumlah,2,".",",");
//         number_format($py->harga_task_order_request)
      }else{
          $dt_jml = number_format($py->jumlah,0,".",",");
      }
      
       $brand = "";
    if($py->name_brand){
        $brand = ", Merk: ".$py->name_brand;
    }
      $nama_barang = $py->nama_barang.$title_spesifik.$brand.$catatan;
      print "<tr>"
        . "<td style='border: 1px solid black;text-align: center;'>{$no}</td>"
        . "<td style='border: 1px solid black;width: 340px;'>{$nama_barang}</td>"
        . "<td align='right' style='border: 1px solid black;'>{$dt_jml}</td>"
        . "<td style='border: 1px solid black;'><center>{$py->satuan}</center></td>"
        . "<td align='right' style='border: 1px solid black;'>".number_format($py->harga_task_order_request)."</td>"
        . "<td style='border: 1px solid black;width:15%'><div style='padding-left:1%'><div style='text-align:left;'>Rp</div><div style='text-align:right;margin-top:-12%'>{$total}</div></div></td>"
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
    $total_sementara = ($total2 - $detail[0]->discount);
    $ppn = (10*$total_sementara)/100;
    if($detail[0]->ppn == 1){
        $dt_ppn = $ppn;
    }else{
        $dt_ppn = 0;
    }
    
    $total_akhir = $total_sementara + $dt_ppn;
    ?>
    <?php if($detail[0]->discount != 0 && $detail[0]->discount != "" || $detail[0]->ppn == 1){?>
    <tr>
      <td colspan="5" style="border: 1px solid black;"><center><b>Sub Total</b></center></td>
      <td style="border: 1px solid black;"><b><div style='text-align:left;'>Rp</div> <div style='text-align:right;margin-top:-12%'><?php print number_format($total2)?></div></b></td>
     <td style="border: 1px solid black;"></td>
    </tr>
    <?php } ?>
    <?php if($detail[0]->discount != 0 && $detail[0]->discount != ""){ ?>
    <tr>
      <td colspan="5" style="border: 1px solid black;"><center><b>Discount</b></center></td>
      <td style="border: 1px solid black;"><b><div style='text-align:left;'>Rp</div> <div style='text-align:right;margin-top:-12%'><?php print number_format($detail[0]->discount)?></div></b></td>
     <td style="border: 1px solid black;"></td>
    </tr>
    <?php }
    if($detail[0]->ppn == 1){
    ?>
    <tr>
      <td colspan="5" style="border: 1px solid black;"><center><b>PPN</b></center></td>
      <td style="border: 1px solid black;"><b><div style='text-align:left;'>Rp</div> <div style='text-align:right;margin-top:-12%'><?php print number_format($dt_ppn)?></div></b></td>
     <td style="border: 1px solid black;"></td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="5" style="border: 1px solid black;"><center><b>Total</b></center></td>
      <td align='right' style="border: 1px solid black;"><b><div style='text-align:left;'>Rp</div> <div style='text-align:right;margin-top:-12%'><?php print number_format($total_akhir)?></div></b></td>
     <td style="border: 1px solid black;"></td>
    </tr>
  </table>
<p></p>
</header>
<footer>
  <table width="100%">
    <tr>
      <td width="30%">Demikian dan terima kasih.<br><br><b>Jakarta, <?php print date("d F Y", strtotime($detail[0]->tanggal_po))?></b><br><br><span style="padding-left: 40px">Hormat Kami</span></td>
      <td style="font-size:13px;">Keterangan :</td>
      <td><br><br><br><br><span style="padding-left: 10px">Diterima oleh</span></td>
    </tr>
    <tr>
      <td>
          <span style="padding-left: 15px"><?php print $sgn2; ?></span>
      </td>
      <td style="font-size:13px;">- Tagihan harus disertai dengan ORDER pembelian asli.<br>- Tanda Terima KWITANSI dilaksanakan setiap hari SELASA
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
     <td width="60%" style="font-size:13px;"><div style="margin-top: 5%"><b><?php print $note."<br>INVOICE A/N. ".strtoupper($detail[0]->company); ?></b></div></td>
     <td width="20%">&nbsp;</td>
    </tr>
  </table>
  </footer>  
</body>

<!--<hr />-->

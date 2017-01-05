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
 <?php
           if($detail[0]->type_inventory == 1){
            $link = site_url("mrp/add-request-pengadaan-cetakan/{$detail[0]->id_mrp_request}");
            $clik = 'window.open(this.href,"_blank"); return false;';
            $url_link = "<a href='{$link}' onclick='{$clik}' >{$detail[0]->code_request}</a>";
        }elseif($detail[0]->type_inventory == 2){
            $link = site_url("mrp/add-request-pengadaan-atk/{$detail[0]->id_mrp_request}");
            $clik = 'window.open(this.href,"_blank"); return false;';
            $url_link = "<a href='{$link}' onclick='{$clik}' >{$detail[0]->code_request}</a>";
        }elseif($detail[0]->type_inventory == 10){
            $link = site_url("mrp/mrp-request/add-request-pengadaan-cetakan-invoice/{$detail[0]->id_mrp_request}");
            $clik = 'window.open(this.href,"_blank"); return false;';
            $url_link = "<a href='{$link}' onclick='{$clik}' >{$detail[0]->code_request}</a>";
        }else{
            $url_link = $detail[0]->code_request;
        }
           ?>
<body onload="window.print();">
<!--<body>-->
<header class="onlyprint">
  <table width="100%" style="padding-bottom: 1%;border: 2">
    <tr>
      <td width="100%"><img src="<?php print base_url()."files/images/logo.png"?>" /><div style="text-align: center;margin-top:-60px"><u><span style="font-size:17px;">FORM PERMINTAAN</span><br><span style="font-size:12px;padding-left: 5px">Cabang/Department: <?php print $detail[0]->struktural; ?><br><?php print $detail[0]->no_po; ?></span></u></div></td>
    </tr>
    <tr>
      <td width="100%"><div style="font-size: 14px; margin-top: 30px"><?php // print $detail[0]->office." - ".$detail[0]->company."<br>".$detail[0]->address_company; ?><br><br>
          </div>
          <div style="text-align: center;padding-left: 600px;margin-top: -100px;"><span style="font-size: 12px;"><?php print $detail[0]->iso; ?></span><br></div>
      <div style="text-align: center;padding-left: 430px;margin-top: 50px;"><span style="font-size: 12px;">Kepada Yth,<br></span><span style="font-size: 12px;">PROCUREMENT</span></div>
      </td>
    </tr>
    <tr>
      <td width="100%"><div style="font-size: 14px; margin-top: 4%">Dengan Hormat,
  <br>Mohon dapat dikirimkan kepada kami <?php  print $tgl;?> barang-barang sbb :</div>
          <div style="padding-left: 550px;margin-top: -1%;"><span style="font-size: 12px;">Kode Request:<?php print $url_link; ?></span></div>
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
      $nama_barang = $py->nama_barang.$title_spesifik.$brand."<br>".nl2br(htmlspecialchars($py->catatan));
      print "<tr>"
        . "<td style='border: 1px solid black;text-align: center;'>{$no}</td>"
        . "<td style='border: 1px solid black;width: 340px;'>{$nama_barang}</td>"
        . "<td style='border: 1px solid black;'><center>{$dt_jml}</center></td>"
        . "<td style='border: 1px solid black;'><center>{$py->satuan}</center></td>"
        . "<td style='border: 1px solid black;'><center>".number_format($py->harga_task_order_request)."</center></td>"
        . "<td style='border: 1px solid black;width: 100px;'>Rp {$total}</td>"
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
      <td style="border: 1px solid black;"><b>Rp <?php print number_format($total2)?></b></td>
     <td style="border: 1px solid black;"></td>
    </tr>
  </table>
<p></p>
</header>
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
    if($detail[0]->users){
        $name_request = $detail[0]->users;
        
    }else{
        $name_request =$detail[0]->pegawai_receiver;
    }
    ?>
    <tr>
      <td><span style="padding-left: 40px">(<?php print $name_request; ?>)</span></td>
      <td>&nbsp;</td>
      <td><span style="margin:0;">(<?php print $detail[0]->nama_user; ?>)</span></td>
    </tr>
    <?php
       if($detail[0]->note){
           $ct_note = "Note: ".$detail[0]->note;
       }
       ?>
   <tr >
      <td width="20%">&nbsp;</td>
     <td width="60%"><div style="margin-top: -2%"><b>Form ini di Approve by Sistem</b></div><br><?php print $ct_note; ?></td>
     <td width="20%">&nbsp;</td>
    </tr>
  </table>
  </footer>  
</body>

<!--<hr />-->

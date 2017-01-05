<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp_po extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
 
public function po($id_mrp_task_orders = 0){

    if(!$this->input->post(NULL)){
        
    $kirim3 = array(
            "status"                        => 1,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => $id_mrp_task_orders, "status" => "2"),$kirim3);

    $url = base_url('mrp/mrp-po/list-po');
    
    $css = ""
            
        . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
        . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
        
            ;
    
    $foot = ""
      
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
                 
            . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-3.1.0.js' type='text/javascript'></script>"
            ;
	
    $foot .= "<script>"
//    . "$(document).ready(function () {"
//      . '$("#desimal1").change(function(){'
//            . "var ischecked= $(this).is(':checked');"
//            . "if(!ischecked){"
//            . "alert('uncheckd ' + $(this).val());"
//            . "}else{"
//            . "alert('check ' + $(this).val());"
//            . "}"
//      . "});"
//    ."});"
               
//            . "});"
                   
    . '$(function() {'
      
    . "$( '#supplier' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-supplier/{$id_mrp_task_orders}")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_supplier').val(ui.item.id);"
           . "var supplier = $('#id_supplier').val();"
            . 'var table = '
            . '$("#tableboxy2").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data(table, 0,supplier);'
         . "}"
       . "});"
            
       . "$( '#company' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-company")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_company').val(ui.item.id);"
         . "}"
       . "});"     
            
//        . "$( '#barang' ).autocomplete({"
//         . "source: '".site_url("mrp/mrp-ajax/get-po-inventory-spesifik")."',"
//         . "minLength: 1,"
//         . "search  : function(){ $(this).addClass('working');},"
//         . "open    : function(){ $(this).removeClass('working');},"
//         . "select: function( event, ui ) {"
//           . "$('#id_barang').val(ui.item.id);"
//            . "$('#id_satuan').val(ui.item.id_satuan);"
////            . "alert($('#id_barang').val());"
//         . "}"
//       . "});"
            
       . "$('#btn-proses').click(function(){"
            . "$('#load-data1').show();"
            . "$('#load-data2').show();"
            . "var oTable = $('#tableboxy2').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData.push( hasil );"
            . "});"
             
            . "var aData1 = [];"
            . "var rowcollection =  oTable.$('.jumlah_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
//            . "var value_jumlah_po = ($(elem).val());"
            . " var value_jumlah_po = parseFloat($(elem).val()).toFixed(2);"
            . " var hasil1 = (isNaN(value_jumlah_po)) ? 0 : value_jumlah_po;"
            . "aData1.push( hasil1 );"
            . "});"
                              
            . "var aData2 = [];"
            . "var rowcollection =  oTable.$('.harga_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_harga_po = parseInt($(elem).val().toString().replace(/\$|\,/g,'') * 1);"
            . " var hasil2 = (isNaN(value_harga_po)) ? 0 : value_harga_po;"
            . "aData2.push( hasil2 );"
            . "});"
                                           
            . "var aData3 = [];"
            . "var rowcollection =  oTable.$('.note_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_note_po = encodeURIComponent($(elem).val());"
            . " var hasil3 = value_note_po;"
            . "aData3.push( hasil3 );"
            . "});"
                            
            . "var aData4 = [];"
            . "var rowcollection =  oTable.$('.id_satuan_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_satuan_po = parseInt($(elem).val());"
            . " var hasil4 = (isNaN(value_id_satuan_po)) ? 0 : value_id_satuan_po;"
            . "aData4.push( hasil4 );"
            . "});"
                            
            . "var aData5 = [];"
            . "var rowcollection =  oTable.$('.id_mrp_inventory_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_inventory_spesifik = parseInt($(elem).val());"
            . " var hasil5 = (isNaN(value_id_mrp_inventory_spesifik)) ? 0 : value_id_mrp_inventory_spesifik;"
            . "aData5.push( hasil5 );"
            . "});"
                 
            . "var aData6 = [];"
            . "var rowcollection =  oTable.$('.catatan_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_catatan_po = encodeURIComponent($(elem).val());"
            . " var hasil6 = value_catatan_po;"
            . "aData6.push( hasil6 );"
            . "});"
                 
            . "var supplier     = $('#id_supplier').val();"
            . "var company      = $('#id_company').val();"
            . "var ppn          = $('#ppn').val();"
            . "var discount     = $('#dt_discount').val();"
            . "var desimal  	= $('#desimal').val();"
                 
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData + '&ppn=' + ppn +'&desimal=' + desimal +'&discount=' + discount + '&catatan='+ aData6 + '&id_mrp_inventory_spesifik='+ aData5 + '&jumlah_po='+ aData1 +'&harga_po='+ aData2 +'&note_po='+ aData3+ '&id_satuan_po='+ aData4 +'&status=4'+'&id_supplier='+ supplier +'&id_company='+ company;"  
                 ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/insert-po")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
//                 . "alert(data);"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
                           
       . "$('#btn-draft').click(function(){"
            . "$('#tombol').hide();"
            . "var oTable = $('#tableboxy2').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData.push( hasil );"
            . "});"
             
            . "var aData1 = [];"
            . "var rowcollection =  oTable.$('.jumlah_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
//            . "var value_jumlah_po = ($(elem).val());"
            . " var value_jumlah_po = parseFloat($(elem).val()).toFixed(2);"                 
            . " var hasil1 = (isNaN(value_jumlah_po)) ? 0 : value_jumlah_po;"
            . "aData1.push( hasil1 );"
            . "});"
                              
            . "var aData2 = [];"
            . "var rowcollection =  oTable.$('.harga_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_harga_po = parseInt($(elem).val().toString().replace(/\$|\,/g,'') * 1);"
            . " var hasil2 = (isNaN(value_harga_po)) ? 0 : value_harga_po;"
            . "aData2.push( hasil2 );"
            . "});"
                                           
            . "var aData3 = [];"
            . "var rowcollection =  oTable.$('.note_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_note_po = encodeURIComponent($(elem).val());"
            . " var hasil3 = value_note_po;"
            . "aData3.push( hasil3 );"
            . "});"
                            
            . "var aData4 = [];"
            . "var rowcollection =  oTable.$('.id_satuan_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_satuan_po = parseInt($(elem).val());"
            . " var hasil4 = (isNaN(value_id_satuan_po)) ? 0 : value_id_satuan_po;"
            . "aData4.push( hasil4 );"
            . "});"
                            
            . "var aData5 = [];"
            . "var rowcollection =  oTable.$('.id_mrp_inventory_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_inventory_spesifik = parseInt($(elem).val());"
            . " var hasil5 = (isNaN(value_id_mrp_inventory_spesifik)) ? 0 : value_id_mrp_inventory_spesifik;"
            . "aData5.push( hasil5 );"
            . "});"
                            
            . "var aData6 = [];"
            . "var rowcollection =  oTable.$('.catatan_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_catatan_po = encodeURIComponent($(elem).val());"
            . " var hasil6 = value_catatan_po;"
            . "aData6.push( hasil6 );"
            . "});"
                            
            . "var supplier = $('#id_supplier').val();"
            . "var company  = $('#id_company').val();"
            . "var desimal  = $('#desimal').val();"                
            . "var ppn          = $('#ppn').val();"
            . "var discount     = $('#dt_discount').val();"                   
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData + '&ppn='+ ppn + '&desimal=' + desimal + '&discount=' + discount + '&catatan=' + aData6 + '&id_mrp_inventory_spesifik='+ aData5 + '&jumlah_po='+ aData1 +'&harga_po='+ aData2 +'&note_po='+ aData3+ '&id_satuan_po='+ aData4 +'&status=3'+'&id_supplier='+ supplier +'&id_company='+ company;"
//            . "alert(dataString2);"                
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/insert-po")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"            
            
        . "var supplier = $('#id_supplier').val();"  
        . 'var table = '
        . '$("#tableboxy2").dataTable({'
          . '"order": [[ 0, "desc" ]],'
        . '});'
        . 'ambil_data(table, 0,supplier,0);'
      . '});'
         
      . "$( '#dt_discount' ).blur(function() {"
      . "var sub_total  = $('#dt-sub-total').text();"
      . "var sub_total2 = sub_total.toString().replace(/\$|\,/g,'');"
      . "var disc = $('#dt_discount').val();"
          . "if(disc == ''){"
                . "disc = 0;"
            . "}else{"
                . "disc = disc;"
            . "}"                   
      . "var disc2 = disc.toString().replace(/\$|\,/g,'');"
      . "var hasil_smntra = parseInt(sub_total2) - parseInt(disc2);"
            
//      . "if(){"
       . "var dx_ppn = $('#ppn').val();"
        . "if(dx_ppn == 1){"
       . "var val_ppn = ((10*hasil_smntra)/100);"
        . "$('#total-ppn').text(parseFloat(val_ppn, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"                 
      . "}else{"
           . "$('#total-ppn').text(0);" 
      . "}"
      . "var hsl_ppn = $('#total-ppn').text();"
      . "var hsl_ppn2 = hsl_ppn.toString().replace(/\$|\,/g,'');"
      . "var hsl_akhir = parseInt(hasil_smntra) + parseInt(hsl_ppn2);"
      . "var tot_disc = $('#total-disc').text(disc);"
      . "$('#dt-total').text(parseFloat(hsl_akhir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
//          . "alert(sub_total);"
      . "});"
      
      . "$('#desimal').change(function(){"
        . "var supplier = $('#id_supplier').val();"
        . "var desimal = $('#desimal').val();"  
        . 'var table = '
        . '$("#tableboxy2").dataTable({'
          . '"order": [[ 0, "desc" ]],'
          . '"bDestroy": true'                  
        . '});'
        . "table.fnClearTable();"                    
        . 'ambil_data(table, 0,supplier,0,desimal);'
      . "});"
                            
      . "$('#ppn').change(function () {"
      . "var sub_total  = $('#dt-sub-total').text();"
      . "var sub_total2 = sub_total.toString().replace(/\$|\,/g,'');"
      . "var disc = $('#dt_discount').val();"
                           
           . "if(disc == ''){"
                . "disc = 0;"
            . "}else{"
                . "disc = disc;"
            . "}"                  
      . "var disc2 = disc.toString().replace(/\$|\,/g,'');"
           
      . "var hasil_smntra = parseInt(sub_total2) - parseInt(disc2);"
            
//      . "if(){"
       . "var dx_ppn = $('#ppn').val();"
        . "if(dx_ppn == 1){"
       . "var val_ppn = ((10*hasil_smntra)/100);"
        . "$('#total-ppn').text(parseFloat(val_ppn, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"                 
      . "}else{"
           . "$('#total-ppn').text(0);" 
      . "}"
      . "var hsl_ppn = $('#total-ppn').text();"
      . "var hsl_ppn2 = hsl_ppn.toString().replace(/\$|\,/g,'');"
      . "var hsl_akhir = parseInt(hasil_smntra) + parseInt(hsl_ppn2);"
      . "var tot_disc = $('#total-disc').text(disc);"
      . "$('#dt-total').text(parseFloat(hsl_akhir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"

      . "});"
                            
      . "function delete_po_task_order_request(id){"
        . "var table = $('#example').DataTable();"
        . "table.row('.selected').remove().draw( false );"
        
//         . "$('#del_'+ id).hide();"
//         . "$('#img-page-'+ id).show();"
//            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ id;"
//               ."$.ajax({"
//               ."type : 'POST',"
//               ."url : '".site_url("mrp/mrp-ajax/delete-po-task-orders-request")."',"
//               ."data: dataString2,"
//               ."dataType : 'html',"
//               ."success: function(data) {"
//                    . "var id_mrp_supplier = $('#id_supplier').val();"
//                       . 'var table = '
//                       . '$("#tableboxy").dataTable({'
//                       . '"order": [[ 0, "asc" ]],'
//                       . '"bDestroy": true'
//                       . '});'
//                       . "table.fnClearTable();"
//                       . 'ambil_data(table, 0,id_mrp_supplier);'
//
//               ."},"
//            ."});"
      . "}"
     . 'function ambil_data(table, mulai,id_mrp_supplier,total,desimal){'
        . "if(typeof id_mrp_supplier == 'undefined'){"
            . "mrp_supplier = 0;"
            . "}else{"
            . "mrp_supplier = id_mrp_supplier;"
         . "}"
       
        . '$.post("'.site_url("mrp/mrp-ajax-po/get-po-request-asset/{$id_mrp_task_orders}").'/"+mulai+"/"+mrp_supplier+"/"+total+"/"+desimal, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,id_mrp_supplier,hasil.dt_total,desimal);'
            . "$('#dt-sub-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
            . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
 
	  . "</script>"; 
        
        $foot .= "<script>
function FormatCurrency(objNum)
{
   var num = objNum.value
   var ent, dec;
   if (num != '' && num != objNum.oldvalue)
   {
     num = MoneyToNumber(num);
     if (isNaN(num))
     {
       objNum.value = (objNum.oldvalue)?objNum.oldvalue:'';
     } else {
       var ev = (navigator.appName.indexOf('Netscape') != -1)?Event:event;
       if (ev.keyCode == 190 || !isNaN(num.split('.')[1]))
       {
        // alert(num.split('.')[1]);
         objNum.value = AddCommas(num.split('.')[0])+'.'+num.split('.')[1];
       }
       else
       {
         objNum.value = AddCommas(num.split('.')[0]);
       }
       objNum.oldvalue = objNum.value;
     }
   }
}
function MoneyToNumber(num)
{
   return (num.replace(/,/g, ''));
}

function AddCommas(num)
{
   numArr=new String(num).split('').reverse();
   for (i=3;i<numArr.length;i+=3)
   {
     numArr[i]+=',';
   }
   return numArr.reverse().join('');
} 
        
function formatCurrency(num) {
   num = num.toString().replace(/\$|\,/g,'');
   if(isNaN(num))
   num = '0';
   sign = (num == (num = Math.abs(num)));
   num = Math.floor(num*10000+0.50000000001);
   cents = num0;
   num = Math.floor(num/100).toString();
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
   num = num.substring(0,num.length-(4*i+3))+'.'+
   num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num);
}
</script>";
        
     $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
     $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
     
     
     $data2 = $this->global_models->get_query("SELECT D.id_hr_company,D.title "
        . " FROM mrp_task_orders_request AS A"
        . " LEFT JOIN mrp_request AS B ON A.id_mrp_request = B.id_mrp_request"
        . " LEFT JOIN hr_pegawai AS C ON B.id_hr_pegawai = C.id_hr_pegawai"
        . " LEFT JOIN hr_company AS D ON C.id_hr_company = D.id_hr_company"
        . " WHERE A.id_mrp_task_orders = '{$id_mrp_task_orders}'"
        . " GROUP BY C.id_hr_company"
        );
        
    $where = "WHERE A.id_mrp_task_orders = '$id_mrp_task_orders'";
    $jml = $this->global_models->get_query("SELECT (A.jumlah - (Select COALESCE(SUM(H.jumlah),0) FROM mrp_po_asset AS H where H.id_mrp_inventory_spesifik  = A.id_mrp_inventory_spesifik GROUP BY H.id_mrp_inventory_spesifik)) AS jumlah_po"
        . " FROM mrp_task_orders_request_asset AS A"
        . " LEFT JOIN mrp_po_asset  AS B ON A.id_mrp_task_orders = B.id_mrp_task_orders"
        . " {$where}"
        );
        
         $where = "WHERE  A.id_mrp_task_orders = '$id_mrp_task_orders'";
        $dt = $this->global_models->get_query("SELECT A.jumlah,"
        . "(Select SUM(H.jumlah) FROM mrp_po_asset AS H where H.id_mrp_inventory_spesifik  = A.id_mrp_inventory_spesifik AND H.status !=12 AND H.id_mrp_task_orders='{$id_mrp_task_orders}'  GROUP BY H.id_mrp_inventory_spesifik) AS jumlah_po"
        . " FROM mrp_task_orders_request_asset AS A"
        . " {$where}"
//        . " GROUP BY A.id_mrp_task_orders,A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_task_orders ASC"
        );
        $jm = 0;
        foreach ($dt as $key => $val) {
            $jm = $jm + ($val->jumlah - $val->jumlah_po);
        }
        
//        print $jm."aa";
//        die;
//        $dt_jml = 0;
//        foreach ($jml as $val) {
//            $dt_jml += $val->jumlah_po;
//        }
//     print "<pre>";
//     print_r($jml);
//     print "</pre>";
//     print $dt_jml;
//     die;
        
//        print $jm;
//        die;
//     $id_hr_company =$this->global_models->get_field("mrp_task_orders_request_asset", "id_hr_company", array("id_mrp_task_orders" => $id_mrp_task_orders));
//     $nama_perusahaan =$this->global_models->get_field("hr_company", "title", array("id_hr_company" => $id_hr_company));
    $this->template->build("main-mrp/add-po", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-task-orders/task-orders',
              'title'       => lang("mrp_add_po"),
              'id_mrp_supplier'  => $id_mrp_supplier,
              'nama_supplier'   => $nama_supplier,
              'nama_perusahaan' => $data2[0]->title,
              'id_perusahaan'   => $data2[0]->id_hr_company,
              'total'           => $jm,
              'breadcrumb'  => array(
                    "mrp_task_orders"  => "mrp/mrp-task-orders/task-orders"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/add-po");
    }
  }
  
    function list_po() {

    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
//          . "$('.dropdown2').select2();"
//        . "$(function() {"
//            . "$( '#brand' ).autocomplete({"
//              . "source: '".site_url("mrp/mrp-ajax/brand")."',"
//              . "minLength: 1,"
//              . "search  : function(){ $(this).addClass('working');},"
//              . "open    : function(){ $(this).removeClass('working');},"
//              . "select: function( event, ui ) {"
//                . "$('#id_brand').val(ui.item.id);"
//              . "}"
//            . "});"
//        . "});"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
        . ' "aaSorting": []'  
//          . '"order": [[ 1, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
      
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-po/get-mrp-list-po").'/"+mulai+"/"+id_users, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'if(hasil.flag === 2){'
                . '$("#loader-page").hide();'
                . '}'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,id_users);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
	  . "</script>";   

//     $pst = $this->input->post(NULL);
//      if($pst){
//
////        $newdata = array(
////            'inventory_spesifik_search_type'                        => $pst['inventory_spesifik_search_type'],
////            'inventory_spesifik_search_jenis'                       => $pst['inventory_spesifik_search_jenis'],
////            'inventory_spesifik_search_nama'                        => $pst['inventory_spesifik_search_nama'],
////            'inventory_spesifik_search_code'                        => $pst['inventory_spesifik_search_code'],
////            'inventory_spesifik_search_brand'                       => $pst['inventory_spesifik_search_brand'],
////            
////          );
////          $this->session->set_userdata($newdata);
//    }
    
    $this->template->build('main-mrp/list-po', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-po/list-po",
            'title'         => lang("Purchase Order"),
            'foot'          => $foot,
            'css'           => $css,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/list-po');
  }
  
  function list_po_umum() {

    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
    
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	  
    $foot .= "<script>"
      . '$(function() {'
        . 'var table = '
        . '$("#tableboxy").dataTable({'
        . ' "aaSorting": []'  
//          . '"order": [[ 1, "desc" ]],'
//          . "'iDisplayLength': 20"
        . '});'
      
        . "ambil_data(table, 0,{$this->session->userdata("id")});"
      
      . '});'
      
      . 'function ambil_data(table, mulai,id_users){'
        . '$.post("'.site_url("mrp/mrp-ajax-po/get-mrp-list-po-umum").'/"+mulai+"/"+id_users, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'if(hasil.flag === 2){'
                . '$("#loader-page").hide();'
                . '}'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,id_users);'
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
	  . "</script>";   

//     $pst = $this->input->post(NULL);
//      if($pst){
//
////        $newdata = array(
////            'inventory_spesifik_search_type'                        => $pst['inventory_spesifik_search_type'],
////            'inventory_spesifik_search_jenis'                       => $pst['inventory_spesifik_search_jenis'],
////            'inventory_spesifik_search_nama'                        => $pst['inventory_spesifik_search_nama'],
////            'inventory_spesifik_search_code'                        => $pst['inventory_spesifik_search_code'],
////            'inventory_spesifik_search_brand'                       => $pst['inventory_spesifik_search_brand'],
////            
////          );
////          $this->session->set_userdata($newdata);
//    }
    
    $this->template->build('main-mrp/list-po', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "mrp/mrp-po/list-po-umum",
            'title'         => lang("Purchase Order"),
            'foot'          => $foot,
            'css'           => $css,
          ));
    $this->template
      ->set_layout('default')
      ->build('main-mrp/list-po');
  }
  
public function update_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
  $status = $this->global_models->get_field("mrp_po", "status",array("id_mrp_po" => "{$id_mrp_po}"));
  
    if($status != 3 AND $status != 8){
        redirect("mrp/mrp-po/detail-po/{$id_mrp_task_orders}/{$id_mrp_po}");
    }
    
    if(!$this->input->post(NULL)){
        
    $kirim3 = array(
            "status"                        => 1,
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
            );
    $this->global_models->update("mrp_po_asset", array("id_mrp_task_orders" => $id_mrp_task_orders, "status" => "2"),$kirim3);

      $data = $this->global_models->get_query("SELECT SUM(B.jumlah) AS jml,SUM(B.jumlah*B.harga) AS total,A.discount,A.ppn,A.id_mrp_po,B.id_mrp_supplier,C.name AS nama_supplier,A.id_hr_company,D.title AS nama_perusahaan"
        . ",A.create_by_users,A.flag_desimal"
        . " FROM mrp_po AS A"
        . " LEFT JOIN mrp_po_asset AS B ON A.id_mrp_po = B.id_mrp_po"
        . " LEFT JOIN mrp_supplier AS C ON B.id_mrp_supplier = C.id_mrp_supplier"
        . " LEFT JOIN hr_company AS D ON A.id_hr_company = D.id_hr_company"   
        . " WHERE B.id_mrp_task_orders ='{$id_mrp_task_orders}' AND A.id_mrp_po = '{$id_mrp_po}'"
        . " GROUP BY A.id_mrp_po "
        . " ORDER BY A.id_mrp_po ASC "
        );
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url('mrp/mrp-po/list-po');
      $url2 = base_url("mrp/mrp-po/update-po/{$id_mrp_task_orders}/{$id_mrp_po}");
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>";
	
    $foot .= "<script>"
                             
      . '$(function() {'
             
    . "$( '#supplier' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-supplier/{$id_mrp_task_orders}")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_supplier').val(ui.item.id);"
           . "var supplier = $('#id_supplier').val();"
            . 'var table = '
            . '$("#tableboxy").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data(table, 0,supplier);'
         . "}"
       . "});"
            
         . "$( '#company' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax/get-company")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_company').val(ui.item.id);"
//            . "alert($('#id_company').val());"
         . "}"
       . "});"
                 
       . "$('#update-po2').click(function(){"
            . "var supplier = $('#id_supplier').val();"
            . 'var table = '
            . '$("#tableboxy2").dataTable({'
            . '"order": [[ 0, "asc" ]],'
            . '"bDestroy": true'
            . '});'
            . "table.fnClearTable();"
            . 'ambil_data2(table, 0,supplier,0);'
       . "});"
            
        . "$( '#barang' ).autocomplete({"
         . "source: '".site_url("mrp/mrp-ajax-po/get-po-inventory-spesifik")."',"
         . "minLength: 1,"
         . "search  : function(){ $(this).addClass('working');},"
         . "open    : function(){ $(this).removeClass('working');},"
         . "select: function( event, ui ) {"
           . "$('#id_barang').val(ui.item.id);"
            . "$('#id_satuan').val(ui.item.id_satuan);"
//            . "alert($('#id_barang').val());"
         . "}"
       . "});"
            
       . "$('#btn-proses').click(function(){"
            . "$('#tombol').hide();"
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_po_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_po_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_po_asset)) ? 0 : value_id_mrp_po_asset;"
            . "aData.push( hasil );"
            . "});"
                              
            . "var company = $('#id_company').val();"
            . "var supplier = $('#id_supplier').val();"
            . "var ppn          = $('#ppn').val();"
            . "var discount     = $('#dt_discount').val();"
            . "var desimal = $('#desimal').val();"      
//            . "var dataString2 = 'id_mrp_task_orders_request_asset='+ aData +'&status=4'+'&id_supplier='+ supplier + '&id_company=' + company ;"
            ."var dataString2 = 'id_mrp_po_asset='+ aData +'&status=4' + '&ppn=' + ppn +'&desimal=' + desimal + '&discount=' + discount +'&id_supplier='+ supplier + '&id_company=' + company ;"
               ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/update-mrp-po/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
                            
       . "$('#save-data-update').click(function(){"
            . "$('#tombol').hide();"                
             . "var oTable = $('#tableboxy2').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_task_orders_request_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_task_orders_request_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_task_orders_request_asset)) ? 0 : value_id_mrp_task_orders_request_asset;"
            . "aData.push( hasil );"
            . "});"
            
            . "var aData1 = [];"
            . "var rowcollection =  oTable.$('.jumlah_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
//            . "var value_jumlah_po = ($(elem).val());"
            . " var value_jumlah_po = parseFloat($(elem).val()).toFixed(2);"                
            . " var hasil1 = (isNaN(value_jumlah_po)) ? 0 : value_jumlah_po;"
            . "aData1.push( hasil1 );"
            . "});"
                              
            . "var aData2 = [];"
            . "var rowcollection =  oTable.$('.harga_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_harga_po = parseInt($(elem).val().toString().replace(/\$|\,/g,'') * 1);"
            . " var hasil2 = (isNaN(value_harga_po)) ? 0 : value_harga_po;"
            . "aData2.push( hasil2 );"
            . "});"
                                           
            . "var aData3 = [];"
            . "var rowcollection =  oTable.$('.note_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_note_po = encodeURIComponent($(elem).val());"
            . " var hasil3 = value_note_po;"
            . "aData3.push( hasil3 );"
            . "});"
                            
            . "var aData4 = [];"
            . "var rowcollection =  oTable.$('.id_satuan_po', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_satuan_po = parseInt($(elem).val());"
            . " var hasil4 = (isNaN(value_id_satuan_po)) ? 0 : value_id_satuan_po;"
            . "aData4.push( hasil4 );"
            . "});"
                            
            . "var aData5 = [];"
            . "var rowcollection =  oTable.$('.id_mrp_inventory_spesifik', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_inventory_spesifik = parseInt($(elem).val());"
            . " var hasil5 = (isNaN(value_id_mrp_inventory_spesifik)) ? 0 : value_id_mrp_inventory_spesifik;"
            . "aData5.push( hasil5 );"
            . "});"
                            
            . "var company = $('#id_company').val();"
//                . "alert(company);"
            . "var supplier = $('#id_supplier').val();"
//            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData +'&status=3'+'&id_supplier='+ supplier +'&id_company=' + company ;"
            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ aData + '&id_mrp_inventory_spesifik='+ aData5 + '&jumlah_po='+ aData1 +'&harga_po='+ aData2 +'&note_po='+ aData3+ '&id_satuan_po='+ aData4 +'&status=3'+'&id_supplier='+ supplier +'&id_company='+ company;"  
               ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/insert-mrp-po/{$id_mrp_po}/{$id_mrp_task_orders}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url2}'"
                ."},"
             ."});"
       . "});"                                        
       . "$('#export-excel').click(function(){"
//       ."var dataString2 = 'id_mrp_po_asset='+ aData +'&status=3'+ '&ppn=' + ppn + '&discount=' + discount +'&id_supplier='+ supplier +'&id_company=' + company ;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/excel-po/$id_mrp_task_orders/{$id_mrp_po}")."',"
//                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
//                    ."window.location ='{$url}'"
                ."},"
             ."});"                   
       . "});"     
       . "$('#btn-save').click(function(){"
            . "$('#tombol').hide();"            
            . "var oTable = $('#tableboxy').dataTable();"
            . "var aData = [];"
            . "var rowcollection =  oTable.$('.mrp_po_asset', {'page': 'all'});"
            . "rowcollection.each(function(index,elem){"
            . "var value_id_mrp_po_asset = parseInt($(elem).val());"
            . " var hasil = (isNaN(value_id_mrp_po_asset)) ? 0 : value_id_mrp_po_asset;"
            . "aData.push( hasil );"
            . "});"
            . "var company = $('#id_company').val();"
//                . "alert(company);"
            . "var supplier = $('#id_supplier').val();"
            . "var ppn          = $('#ppn').val();"
            . "var discount     = $('#dt_discount').val();"
            . "var desimal = $('#desimal').val();"                
            ."var dataString2 = 'id_mrp_po_asset='+ aData +'&status=3'+ '&ppn=' + ppn +'&desimal='+ desimal + '&discount=' + discount +'&id_supplier='+ supplier +'&id_company=' + company ;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/update-mrp-po/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
            
            
        . "var supplier = $('#id_supplier').val();"
        . "var desimal = $('#desimal').val();"                     
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "asc" ]],'
        . '});'
        . 'ambil_data(table, 0,supplier,0,desimal);'
      . '});'
          . "$( '#dt_discount' ).blur(function() {"
      . "var sub_total  = $('#dt-sub-total').text();"
      . "var sub_total2 = sub_total.toString().replace(/\$|\,/g,'');"
      . "var disc = $('#dt_discount').val();"
          . "if(disc == ''){"
                . "disc = 0;"
            . "}else{"
                . "disc = disc;"
            . "}"                   
      . "var disc2 = disc.toString().replace(/\$|\,/g,'');"
      . "var hasil_smntra = parseInt(sub_total2) - parseInt(disc2);"
            
//      . "if(){"
       . "var dx_ppn = $('#ppn').val();"
        . "if(dx_ppn == 1){"
       . "var val_ppn = ((10*hasil_smntra)/100);"
        . "$('#total-ppn').text(parseFloat(val_ppn, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"                 
      . "}else{"
           . "$('#total-ppn').text(0);" 
      . "}"
      . "var hsl_ppn = $('#total-ppn').text();"
      . "var hsl_ppn2 = hsl_ppn.toString().replace(/\$|\,/g,'');"
      . "var hsl_akhir = parseInt(hasil_smntra) + parseInt(hsl_ppn2);"
      . "var tot_disc = $('#total-disc').text(disc);"
      . "$('#dt-total').text(parseFloat(hsl_akhir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
//          . "alert(sub_total);"
      . "});"
                            
       . "$('#desimal').change(function(){"
        . "var supplier = $('#id_supplier').val();"
        . "var desimal = $('#desimal').val();"  
        . 'var table = '
        . '$("#tableboxy").dataTable({'
          . '"order": [[ 0, "desc" ]],'
          . '"bDestroy": true'                  
        . '});'
        . "table.fnClearTable();"                    
        . 'ambil_data(table, 0,supplier,0,desimal);'
      . "});"
                            
      . "$('#ppn').change(function () {"
      . "var sub_total  = $('#dt-sub-total').text();"
      . "var sub_total2 = sub_total.toString().replace(/\$|\,/g,'');"
      . "var disc = $('#dt_discount').val();"
                           
           . "if(disc == ''){"
                . "disc = 0;"
            . "}else{"
                . "disc = disc;"
            . "}"                  
      . "var disc2 = disc.toString().replace(/\$|\,/g,'');"
           
      . "var hasil_smntra = parseInt(sub_total2) - parseInt(disc2);"
            
//      . "if(){"
       . "var dx_ppn = $('#ppn').val();"
        . "if(dx_ppn == 1){"
       . "var val_ppn = ((10*hasil_smntra)/100);"
        . "$('#total-ppn').text(parseFloat(val_ppn, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"                 
      . "}else{"
           . "$('#total-ppn').text(0);" 
      . "}"
      . "var hsl_ppn = $('#total-ppn').text();"
      . "var hsl_ppn2 = hsl_ppn.toString().replace(/\$|\,/g,'');"
      . "var hsl_akhir = parseInt(hasil_smntra) + parseInt(hsl_ppn2);"
      . "var tot_disc = $('#total-disc').text(disc);"
      . "$('#dt-total').text(parseFloat(hsl_akhir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"

      . "});"
                            
      . "function delete_po_task_order_request(id){"
//         . "$('#del_'+ id).hide();"
//         . "$('#img-page-'+ id).show();"
//            ."var dataString2 = 'id_mrp_task_orders_request_asset='+ id;"
//               ."$.ajax({"
//               ."type : 'POST',"
//               ."url : '".site_url("mrp/mrp-ajax-po/delete-po-task-orders-request/")."',"
//               ."data: dataString2,"
//               ."dataType : 'html',"
//               ."success: function(data) {"
////                    . "var id_mrp_supplier = $('#id_supplier').val();"
////                       . 'var table = '
////                       . '$("#tableboxy2").dataTable({'
////                       . '"order": [[ 0, "asc" ]],'
////                       . '"bDestroy": true'
////                       . '});'
////                       . "table.fnClearTable();"
////                       . 'ambil_data2(table, 0,id_mrp_supplier);'
////                            
//////                       . 'var table = '
////                       . '$("#tableboxy").dataTable().fnReloadAjax();'
////                       . "table.fnClearTable();"
////                       . 'ambil_data(table, 0,id_mrp_supplier);'
//                                 
//               ."},"
//            ."});"
      . "}"
                            
      . "function delete_po_task_order_request2(id){"
//         . "$('#del2_'+ id).hide();"
//         . "$('#img-page2-'+ id).show();"
            ."var dataString2 = 'id_mrp_po_asset='+ id;"
               ."$.ajax({"
               ."type : 'POST',"
               ."url : '".site_url("mrp/mrp-ajax-po/delete-po-task-orders-request2/")."',"
               ."data: dataString2,"
               ."dataType : 'html',"
               ."success: function(data) {"
//                    . "var id_mrp_supplier = $('#id_supplier').val();"
//                       . 'var table = '
//                       . '$("#tableboxy").dataTable({'
//                       . '"order": [[ 0, "asc" ]],'
//                       . '"bDestroy": true'
//                       . '});'
//                       . "table.fnClearTable();"
//                       . 'ambil_data(table, 0,id_mrp_supplier);'
//                            
//                       . 'var table = '
//                       . '$("#tableboxy2").dataTable({'
//                       . '"bDestroy": true,'
//                       . '"bJQueryUI": true,'    
//                       . '});'
//                       . "table.fnClearTable();"
//                       . 'ambil_data2(table, 0,id_mrp_supplier);'
               ."},"
            ."});"
      . "}"                      
                            
     . 'function ambil_data(table, mulai,id_mrp_supplier,total,desimal){'
        . "if(typeof id_mrp_supplier == 'undefined'){"
            . "mrp_supplier = 0;"
            . "}else{"
            . "mrp_supplier = id_mrp_supplier;"
         . "}"
       
        . '$.post("'.site_url("mrp/mrp-ajax-po/get-update-po-request-asset/{$id_mrp_task_orders}/{$id_mrp_po}").'/"+mulai+"/"+mrp_supplier+"/"+total+"/"+desimal, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start,mrp_supplier,hasil.dt_total,desimal);'
            . "$('#dt-sub-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
//            . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
                
      . 'function ambil_data2(table, mulai,id_mrp_supplier,total){'
        . "if(typeof id_mrp_supplier == 'undefined'){"
            . "mrp_supplier = 0;"
            . "}else{"
            . "mrp_supplier = id_mrp_supplier;"
         . "}"
       
        . '$.post("'.site_url("mrp/mrp-ajax-po/get-po-request-asset/{$id_mrp_task_orders}").'/"+mulai+"/"+mrp_supplier+"/"+ total, function(data){'
          . '$("#loader-page2").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
                ."if(hasil.angka > 0){"   
                    . 'table.fnAddData(hasil.hasil);'
               ."}"
              . 'ambil_data2(table, hasil.start,id_mrp_supplier,total);'
          . '}'
          . 'else{'
            . '$("#loader-page2").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(data);"
          . '}'
        . '});'
      . '}'
      . "var sub_total  = $('#dt-sub-total').text();"
      . "var sub_total2 = sub_total.toString().replace(/\$|\,/g,'');"
      . "var disc = $('#dt_discount').val();"
                           
           . "if(disc == ''){"
                . "disc = 0;"
            . "}else{"
                . "disc = disc;"
            . "}"                  
      . "var disc2 = disc.toString().replace(/\$|\,/g,'');"
           
      . "var hasil_smntra = parseInt(sub_total2) - parseInt(disc2);"
            
//      . "if(){"
       . "var dx_ppn = $('#ppn').val();"
        . "if(dx_ppn == 1){"
       . "var val_ppn = parseInt((10*hasil_smntra)/100);"
               
        . "$('#total-ppn').text(parseFloat(val_ppn, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"                 
      . "}else{"
           . "$('#total-ppn').text(0);" 
      . "}"
      . "var hsl_ppn = $('#total-ppn').text();"
      . "var hsl_ppn2 = hsl_ppn.toString().replace(/\$|\,/g,'');"
      . "var hsl_akhir = parseInt(hasil_smntra) + parseInt(hsl_ppn2);"
      . "var tot_disc = $('#total-disc').text(disc);"
      . "$('#dt-total').text(parseFloat(hsl_akhir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          
	  . "</script>"; 
        
        $foot .= "<script>
function FormatCurrency(objNum)
{
   var num = objNum.value
   var ent, dec;
   if (num != '' && num != objNum.oldvalue)
   {
     num = MoneyToNumber(num);
     if (isNaN(num))
     {
       objNum.value = (objNum.oldvalue)?objNum.oldvalue:'';
     } else {
       var ev = (navigator.appName.indexOf('Netscape') != -1)?Event:event;
       if (ev.keyCode == 190 || !isNaN(num.split('.')[1]))
       {
        // alert(num.split('.')[1]);
         objNum.value = AddCommas(num.split('.')[0])+'.'+num.split('.')[1];
       }
       else
       {
         objNum.value = AddCommas(num.split('.')[0]);
       }
       objNum.oldvalue = objNum.value;
     }
   }
}
function MoneyToNumber(num)
{
   return (num.replace(/,/g, ''));
}

function AddCommas(num)
{
   numArr=new String(num).split('').reverse();
   for (i=3;i<numArr.length;i+=3)
   {
     numArr[i]+=',';
   }
   return numArr.reverse().join('');
} 
        
function formatCurrency(num) {
   num = num.toString().replace(/\$|\,/g,'');
   if(isNaN(num))
   num = '0';
   sign = (num == (num = Math.abs(num)));
   num = Math.floor(num*10000+0.50000000001);
   cents = num0;
   num = Math.floor(num/100).toString();
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
   num = num.substring(0,num.length-(4*i+3))+'.'+
   num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num);
}
</script>";
        
    $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
    $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
//     $id_mrp_supplier =$this->global_models->get_field("mrp_po", "id_mrp_po", array("id_mrp_task_orders" => $id_mrp_task_orders));
    
    
//        print_r($data);
//        die;
//        $where = "WHERE A.status = 1 AND A.id_mrp_task_orders = '$id_mrp_task_orders'";
//        $jml = $this->global_models->get_query("SELECT count(A.id_mrp_task_orders_request_asset) AS id"
//        . " FROM mrp_po_asset AS A"
//        . " {$where}"
//        );
        
        $where = "WHERE  A.id_mrp_task_orders = '$id_mrp_task_orders'";
        $dt = $this->global_models->get_query("SELECT A.jumlah,"
        . "(Select SUM(H.jumlah) FROM mrp_po_asset AS H where H.id_mrp_inventory_spesifik  = A.id_mrp_inventory_spesifik AND H.status !=12 AND H.id_mrp_task_orders ='{$id_mrp_task_orders}' GROUP BY H.id_mrp_inventory_spesifik) AS jumlah_po"
        . " FROM mrp_task_orders_request_asset AS A"
        . " {$where}"
        . " GROUP BY A.id_mrp_task_orders,A.id_mrp_inventory_spesifik"
        . " ORDER BY A.id_mrp_task_orders ASC"
        );
//        print_r($dt);
//      print $this->db->last_query();
//      die;
        $jm = 0;
        $dt_anka = 0;
        $ttl = 0;
        foreach ($dt as $key => $val) {
            if($val->jumlah_po){
                $jumlah_po = $val->jumlah_po;
            }else{
                $jumlah_po = 0;
            }
            $jm = ($val->jumlah - $jumlah_po);
            if($jm > 0){
               $dt_anka = 1;
            }else{
              $dt_anka = 0;
            }
            $ttl = $ttl + $dt_anka;
        }
//        print $jm;
//        print $ttl; die;
        
//        print_r($data); die;
     $this->template->build("main-mrp/update-po/view-update-po", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/mrp-po/list-po',
              'title'       => lang("mrp_add_po"),
              'id_mrp_supplier'     => $data[0]->id_mrp_supplier,
              'nama_supplier'       => $data[0]->nama_supplier,
              'id_perusahaan'       => $data[0]->id_hr_company,
              'nama_perusahaan'     => $data[0]->nama_perusahaan,
              'id_users'            => $data[0]->create_by_users,
              'data'                => $data[0],
              'total'               => $ttl,
              'breadcrumb'  => array(
                    "list-po"  => 'mrp/mrp-po/list-po'
                ),
              'css'         => $css,
              'foot'        => $foot,
              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/update-po/view-update-po");
    }else{
        $pst = $this->input->post();
        if($pst['export'] == "Export Excel"){
            $this->load->model('mrp/mmrp_po');
      $this->mmrp_po->po_export_xls($id_mrp_task_orders,$id_mrp_po,"Data-PO");
      die;
        }
    }
   
  }
  
  function delete_list_po($id){
    
      $this->global_models->delete("mrp_po_asset", array("id_mrp_po" => $id));
    $this->global_models->delete("mrp_po", array("id_mrp_po" => $id));
    
        
    $this->session->set_flashdata('success', 'Data Berhasil di Hapus');
     redirect("mrp/mrp-po/list-po");
    die;
  }
  
   public function detail_po($id_mrp_task_orders = 0,$id_mrp_po = 0){
//   print $tgl = "VTHO/PO/91/07/2016";
//          print  $tahun ="<br>".substr($tgl,-4)."<br>";
//           print $bulan =substr($tgl,-10,-8);
//   die;
//   $pp = "VTHO/PO/00/07/2016";
//   $arr_dt =explode("/",$pp);
//   print_r($arr_dt);
//   die;
     
//    $as = $this->global_models->get_query("SELECT D.id_mrp_request,A.id_mrp_inventory_spesifik FROM mrp_po_asset AS A"
//             . " LEFT JOIN mrp_task_orders_request_asset AS B ON A.id_mrp_task_orders_request_asset = B.id_mrp_task_orders_request_asset"
//             . " LEFT JOIN mrp_task_orders_request AS C ON B.id_mrp_task_orders = C.id_mrp_task_orders"
//             . " LEFT JOIN mrp_request_asset AS D ON (C.id_mrp_request = E.id_mrp_request AND A.id_mrp_inventory_spesifik = E.id_mrp_inventory_spesifik)" 
//             . " WHERE C.id_mrp_task_orders='{$id_mrp_task_orders}' AND A.id_mrp_po='{$id_mrp_po}' AND E.id_mrp_request IS NOT NULL ");
//     print_r($as);
//     die;
    if(!$this->input->post(NULL)){
        
//    $list = $this->global_models->get_query("SELECT A.id_mrp_po,A.ppn,A.discount,A.frm,A.no_po,A.code,A.status,A.note,A.tanggal_dikirim,A.tanggal_payment,A.note_payment,A.status_payment,A.tanggal_po"
//        . ",B.name,B.pic,B.phone,B.fax,B.address,B.id_mrp_supplier,A.user_approval,A.user_checker"
//        . ",C.title AS nama_perusahaan,C.office,C.address AS alamat_perusahaan,C.id_hr_company,D.name AS name_checker"
//        . ",A.flag_desimal,SUM(E.jumlah) AS jml,SUM(E.jumlah*E.harga) AS total,(SELECT concat(L.tanggal_diterima,',',K.id_mrp_receiving_goods_po) FROM mrp_receiving_goods_po AS K"
//            . " LEFT JOIN mrp_receiving_goods AS L ON K.id_mrp_receiving_goods_po = L.id_mrp_receiving_goods_po"
//            . " WHERE K.id_mrp_po = A.id_mrp_po "
//            . " GROUP BY K.id_mrp_po"
//            . " ORDER BY L.tanggal_diterima ASC LIMIT 0,1) AS date_receive"
//        . " FROM mrp_po AS A"
//        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
//        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"
//        . " LEFT JOIN m_users AS D ON A.user_checker = D.id_users"
//        . " LEFT JOIN mrp_po_asset AS E  ON A.id_mrp_po = E.id_mrp_po"    
//        . " WHERE A.id_mrp_po = '{$id_mrp_po}' "
//        . " GROUP BY A.id_mrp_po "
//       );
        
        $list = $this->global_models->get_query("SELECT * FROM v_detail_po "
                . " WHERE id_mrp_po='{$id_mrp_po}'");
        
//    print $this->db->last_query();
//    die;
        
        if($list[0]->frm){
            $frm = $list[0]->frm;
        }else{
            $frm = "FRM/AV/PC/1, Rev:0";
        }
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url('mrp/mrp-po/list-po');
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
                               
      . '$(function() {'
             . "$( '.date_payment' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
                        
            . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "onSelect: function(){"
              . "var tgl_kirim = $('#tgl_dikirim').val();"
            . "var tgl_po = $('#tgl_po').val();"
            . "var frm = $('#id_frm').val();"
            . "var dt_note = encodeURIComponent($('#note_po').val());"  
            ."var dataString2 = 'tanggal='+tgl_kirim + '&frm='+ frm +'&note=' + dt_note+ '&tanggal_po='+ tgl_po;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/update-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                . 'var hasil = $.parseJSON(data);'
//                  . "alert(hasil);"
                   . "$('#no_po').text(hasil.no_po);"        
                ."},"
            ."});"
              . "}"
              . "});"
                        
//        . "$('#export-excel').click(function(){"
////       ."var dataString2 = 'id_mrp_po_asset='+ aData +'&status=3'+ '&ppn=' + ppn + '&discount=' + discount +'&id_supplier='+ supplier +'&id_company=' + company ;"
////                ."$.ajax({"
////                ."type : 'POST',"
////                ."url : '".site_url("mrp/mrp-ajax-po/excel-po/$id_mrp_task_orders/{$id_mrp_po}")."',"
//////                ."data: dataString2,"
////                ."dataType : 'html',"
////                ."success: function(data) {"
////                    ."window.location ='{$url}'"
////                ."},"
////             ."});"
//        . 'function ambil_data(table, mulai,total){'
//        . '$.post("'.site_url("mrp/mrp-ajax-po/excel-po/{$id_mrp_task_orders}/{$id_mrp_po}").', function(data){'
//          . 'var hasil = $.parseJSON(data);'
//          . 'if(hasil.status == 2){'
//          . '}'
//          . 'else{'
////            . '$("#loader-page").hide();'
////            . 'load_tooltip();'
////            . 'load_tooltip_harga();'
////            . "$('#script-tambahan').html(hasil.dt_total);"
//          . '}'
//        . '});'
//      . '}'                    
//       . "});"
                        
        . "$('#id_frm').blur(function(){"
            . "var frm = encodeURIComponent($('#id_frm').val());"
             . "var tgl_kirim = $('#tgl_dikirim').val();"
            . "var tgl_po = $('#tgl_po').val();"           
            . "var dt_note = encodeURIComponent($('#note_po').val());"            
            ."var dataString2 = 'tanggal='+tgl_kirim +'&note=' + dt_note+ '&tanggal_po='+ tgl_po + '&frm='+frm;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/update-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                ."},"
            ."});"            
        . "});"
                           
       . "$('#note_po').blur(function(){"
            . "var tgl_kirim = $('#tgl_dikirim').val();"
            . "var tgl_po = $('#tgl_po').val();"           
            . "var dt_note = encodeURIComponent($('#note_po').val());"
            . "var frm = encodeURIComponent($('#id_frm').val());"
             ."var dataString2 = 'tanggal='+tgl_kirim +'&note=' + dt_note+ '&tanggal_po='+ tgl_po + '&frm='+frm;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/update-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                ."},"
            ."});"
       . "});"
            
       . "$('#btn-approval').click(function(){"
            . "var tgl_po = $('#tgl_po').val();"
            . "var frm = $('#id_frm').val();"
//                        . "alert(tgl_po);"
            ."var dataString2 = 'status=5'+ '&tanggal='+ tgl_po +'&frm='+ frm +'&id_company={$list[0]->id_hr_company}';"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/approve-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
                            
    . "$('#btn-send').click(function(){"
//           . "alert();"
          . "if($('#blast').is(':checked')){"
             . "var blast_email = 1;"
            . "}else{"
             . "var blast_email = 2;"
         . "}"
//         . "alert(blast_email);"
          . " var tgl_po = $('#tgl_po').val();"
         . " var frm = $('#id_frm').val();"                
         ."var dataString2 = 'status=6' + '&tanggal='+ tgl_po +'&frm='+ frm+'&blast_eml='+ blast_email;"
             ."$.ajax({"
             ."type : 'POST',"
             ."url : '".site_url("mrp/mrp-ajax-po/approve-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
             ."data: dataString2,"
             ."dataType : 'html',"
             ."success: function(data) {"
                 ."window.location ='{$url}'"
             ."},"
          ."});"
    . "});"
       
       . "$('#btn-revisi').click(function(){"
            ."var dataString2 = 'status=8';"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/approve-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
       
        . "$('#btn-closed-po').click(function(){"
            ."var dataString2 = 'status=7';"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/closed-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                    ."window.location ='{$url}'"
                ."},"
             ."});"
       . "});"
            
        . 'var table = '
        . '$("#tableboxy").dataTable({'
        . '"order": [[ 0, "asc" ]],'
        . '});'
        . "ambil_data(table, 0, 0);"
      . '});'
            
     . 'function ambil_data(table, mulai,total){'
        . '$.post("'.site_url("mrp/mrp-ajax-po/get-mrp-detail-po-list/{$id_mrp_task_orders}/{$id_mrp_po}/{$list[0]->status}").'/"+mulai+"/"+total, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start, hasil.dt_total);'
//           . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
           . "$('#dt-sub-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
      . '}'
                
      . "var sub_total  = $('#dt-sub-total').text();"
      . "var sub_total2 = sub_total.toString().replace(/\$|\,/g,'');"
      . "var disc = $('#dt_discount').text();"
                           
           . "if(disc == ''){"
                . "disc = 0;"
            . "}else{"
                . "disc = disc;"
            . "}"                  
      . "var disc2 = disc.toString().replace(/\$|\,/g,'');"
           
      . "var hasil_smntra = parseInt(sub_total2) - parseInt(disc2);"
            
//      . "if(){"
       . "var dx_ppn = $('#ppn').text();"
        . "if(dx_ppn == 1){"
       . "var val_ppn = parseInt((10*hasil_smntra)/100);"
               
        . "$('#total-ppn').text(parseFloat(val_ppn, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"                 
      . "}else{"
           . "$('#total-ppn').text(0);" 
      . "}"
      . "var hsl_ppn = $('#total-ppn').text();"
      . "var hsl_ppn2 = hsl_ppn.toString().replace(/\$|\,/g,'');"
      . "var hsl_akhir = parseInt(hasil_smntra) + parseInt(hsl_ppn2);"
      . "var tot_disc = $('#total-disc').text(disc);"
      . "$('#dt-total').text(parseFloat(hsl_akhir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                    
	  . "</script>"; 
        
//    $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
//    $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
//     $id_mrp_task_orders = 0,$id_mrp_po 
         $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-green'>Sent PO</span>"
             , 7 =>"<span class='label bg-green'>Closed PO</span>", 8 =>"<span class='label bg-red'>Revisi PO</span>",
             12 =>"<span class='label bg-red'>Cancel PO</span>");
         
    if($list[0]->status == 6){
       $dt = $this->global_models->get_query("SELECT id_blast_email_po FROM mrp_receiving_goods_po AS A"
                . " LEFT JOIN blast_email_po AS B ON A.id_mrp_receiving_goods_po = B.id_mrp_receiving_goods_po"
                . " WHERE id_mrp_po ='{$id_mrp_po}' "
                );
        
//        print_r($dt);
//        die;
    }
    
    $this->template->build("main-mrp/detail-po/view-detail", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/mrp-po/list-po',
              'title'               => lang("mrp_detail_po"),
              'list'                => $list,
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'id_mrp_po'           => $id_mrp_po,
              'dt_status'           => $status,
              'frm'                 => $frm,
              'id_blast_email_po'   => $dt[0]->id_blast_email_po,
              'breadcrumb'  => array(
                    "mrp_list_po"  => "mrp/mrp-po/list-po"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/detail-po/view-detail");
    }else{
      $pst = $this->input->post(NULL);
      
//      print_r($pst);
//      die;
      
      if($pst['export'] == "Export Excel"){
      $this->load->model('mrp/mmrp_po');
      $this->mmrp_po->po_export_xls($id_mrp_task_orders,$id_mrp_po,"Data-PO");
      }
      
      if($pst['b_email'] == "Blast Email"){
         $get = $this->global_models->get("mrp_receiving_goods_po",array("id_mrp_po" => "{$pst['id_detail']}"));
         
         $this->db->query("UPDATE mrp_request SET status_blast=1 WHERE id_mrp_request IN({$get[0]->array_id_mrp_request}) ");
//         $this->global_models->get_query;
//        print $this->db->last_query();
//        die;
         $kirim = array(
            "id_mrp_receiving_goods_po"         => $get[0]->id_mrp_receiving_goods_po,
            "status"                            => 1,
            "reminder"                          => 1,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s")
        );
       $this->global_models->insert("blast_email_po", $kirim);
       
      }
      
      if($pst['btn_cancel'] == "cancel_po"){
        $this->load->model('mrp/mmrp');
        $this->mmrp->cancel_po($pst["id_detail"]);
      }
      
      if($pst['user_checker']){
          $kirim = array(
            "user_checker"                  => $this->session->userdata("id"),
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_mrp_po4 = $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim);
      }
      
      if($id_mrp_po){
       $kirim = array(
            "tanggal_payment"               => $pst['tgl_payment'],
            "note_payment"                  => $pst['note_pembayaran'],
            "status_payment"                => $pst['status_pembayaran'],
            "update_by_users"               => $this->session->userdata("id"),
            "update_date"                   => date("Y-m-d H:i:s")
        );
        
        $id_mrp_po4 = $this->global_models->update("mrp_po", array("id_mrp_po" => $id_mrp_po),$kirim);
      
      }
     
      if($id_mrp_po4){
        $this->session->set_flashdata('success', 'Data di update');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/mrp-po/detail-po/{$id_mrp_task_orders}/{$id_mrp_po}");
    }
   
  }
  
 public function detail_po_umum($id_mrp_task_orders = 0,$id_mrp_po = 0){

    if(!$this->input->post(NULL)){
        
//    $list = $this->global_models->get_query("SELECT A.id_mrp_po,A.ppn,A.discount,A.frm,A.no_po,A.code,A.status,A.note,A.tanggal_dikirim,A.tanggal_payment,A.note_payment,A.status_payment,A.tanggal_po"
//        . ",B.name,B.pic,B.phone,B.fax,B.address,B.id_mrp_supplier,A.user_approval,A.user_checker"
//        . ",C.title AS nama_perusahaan,C.office,C.address AS alamat_perusahaan,C.id_hr_company,D.name AS name_checker"
//        . ",A.flag_desimal,SUM(E.jumlah) AS jml,SUM(E.jumlah*E.harga) AS total,(SELECT concat(L.tanggal_diterima,',',K.id_mrp_receiving_goods_po) FROM mrp_receiving_goods_po AS K"
//            . " LEFT JOIN mrp_receiving_goods AS L ON K.id_mrp_receiving_goods_po = L.id_mrp_receiving_goods_po"
//            . " WHERE K.id_mrp_po = A.id_mrp_po "
//            . " GROUP BY K.id_mrp_po"
//            . " ORDER BY L.tanggal_diterima ASC LIMIT 0,1) AS date_receive"
//        . " FROM mrp_po AS A"
//        . " LEFT JOIN mrp_supplier AS B ON A.id_mrp_supplier = B.id_mrp_supplier"
//        . " LEFT JOIN hr_company AS C ON A.id_hr_company = C.id_hr_company"
//        . " LEFT JOIN m_users AS D ON A.user_checker = D.id_users"
//        . " LEFT JOIN mrp_po_asset AS E  ON A.id_mrp_po = E.id_mrp_po"    
//        . " WHERE A.id_mrp_po = '{$id_mrp_po}' "
//        . " GROUP BY A.id_mrp_po "
//       );
        
        $list = $this->global_models->get_query("SELECT * FROM v_detail_po "
                . " WHERE id_mrp_po='{$id_mrp_po}'");
        
//    print $this->db->last_query();
//    die;
        
        if($list[0]->frm){
            $frm = $list[0]->frm;
        }else{
            $frm = "FRM/AV/PC/1, Rev:0";
        }
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $url = base_url('mrp/mrp-po/list-po');
    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
     ;
	
    $foot .= "<script>"
                               
      . '$(function() {'
             . "$( '.date_payment' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "});"
                        
            . "$( '.date' ).datepicker({"
              . "showOtherMonths: true,"
              . "dateFormat: 'yy-mm-dd',"  
              . "selectOtherMonths: true,"  
              . "selectOtherYears: true,"
              . "onSelect: function(){"
              . "var tgl_kirim = $('#tgl_dikirim').val();"
            . "var tgl_po = $('#tgl_po').val();"
            . "var frm = $('#id_frm').val();"
            . "var dt_note = encodeURIComponent($('#note_po').val());"  
            ."var dataString2 = 'tanggal='+tgl_kirim + '&frm='+ frm +'&note=' + dt_note+ '&tanggal_po='+ tgl_po;"
                ."$.ajax({"
                ."type : 'POST',"
                ."url : '".site_url("mrp/mrp-ajax-po/update-detail-po/{$id_mrp_task_orders}/{$id_mrp_po}")."',"
                ."data: dataString2,"
                ."dataType : 'html',"
                ."success: function(data) {"
                . 'var hasil = $.parseJSON(data);'
//                  . "alert(hasil);"
                   . "$('#no_po').text(hasil.no_po);"        
                ."},"
            ."});"
              . "}"
              . "});"
      
        . 'var table = '
        . '$("#tableboxy").dataTable({'
        . '"order": [[ 0, "asc" ]],'
        . '});'
        . "ambil_data(table, 0, 0);"
      . '});'
            
     . 'function ambil_data(table, mulai,total){'
        . '$.post("'.site_url("mrp/mrp-ajax-po/get-mrp-detail-po-list-umum/{$id_mrp_task_orders}/{$id_mrp_po}/{$list[0]->status}").'/"+mulai+"/"+total, function(data){'
          . '$("#loader-page").show();'
          . 'var hasil = $.parseJSON(data);'
          . 'if(hasil.status == 2){'
            . 'table.fnAddData(hasil.hasil);'
            . 'ambil_data(table, hasil.start, hasil.dt_total);'
//           . "$('#dt-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
           . "$('#dt-sub-total').text(parseFloat(hasil.dt_total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
          . '}'
          . 'else{'
            . '$("#loader-page").hide();'
//            . 'load_tooltip();'
//            . 'load_tooltip_harga();'
//            . "$('#script-tambahan').html(hasil.dt_total);"
          . '}'
        . '});'
      . '}'
                
      . "var sub_total  = $('#dt-sub-total').text();"
      . "var sub_total2 = sub_total.toString().replace(/\$|\,/g,'');"
      . "var disc = $('#dt_discount').text();"
                           
           . "if(disc == ''){"
                . "disc = 0;"
            . "}else{"
                . "disc = disc;"
            . "}"                  
      . "var disc2 = disc.toString().replace(/\$|\,/g,'');"
           
      . "var hasil_smntra = parseInt(sub_total2) - parseInt(disc2);"
            
//      . "if(){"
       . "var dx_ppn = $('#ppn').text();"
        . "if(dx_ppn == 1){"
       . "var val_ppn = parseInt((10*hasil_smntra)/100);"
               
        . "$('#total-ppn').text(parseFloat(val_ppn, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"                 
      . "}else{"
           . "$('#total-ppn').text(0);" 
      . "}"
      . "var hsl_ppn = $('#total-ppn').text();"
      . "var hsl_ppn2 = hsl_ppn.toString().replace(/\$|\,/g,'');"
      . "var hsl_akhir = parseInt(hasil_smntra) + parseInt(hsl_ppn2);"
      . "var tot_disc = $('#total-disc').text(disc);"
      . "$('#dt-total').text(parseFloat(hsl_akhir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').toString());"
                    
	  . "</script>"; 
        
//    $id_mrp_supplier =$this->global_models->get_field("mrp_task_orders_request_asset", "id_mrp_supplier", array("id_mrp_task_orders" => $id_mrp_task_orders));
//    $nama_supplier =$this->global_models->get_field("mrp_supplier", "name", array("id_mrp_supplier" => $id_mrp_supplier));
//     $id_mrp_task_orders = 0,$id_mrp_po 
         $status = array( 3=> "<span class='label bg-orange'>Draft PO</span>", 4 => "<span class='label bg-green'>Proses Pengajuan PO</span>",
      5 => "<span class='label bg-green'>Approved PO</span>", 6 =>"<span class='label bg-green'>Sent PO</span>"
             , 7 =>"<span class='label bg-green'>Closed PO</span>", 8 =>"<span class='label bg-red'>Revisi PO</span>",
             12 =>"<span class='label bg-red'>Cancel PO</span>");
         
    $this->template->build("main-mrp/detail-po-umum/view-detail", 
        array(
              'url'                 => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'                => 'mrp/mrp-po/list-po-umum',
              'title'               => lang("detail_po_umum"),
              'list'                => $list,
              'id_mrp_task_orders'  => $id_mrp_task_orders,
              'id_mrp_po'           => $id_mrp_po,
              'dt_status'           => $status,
              'frm'                 => $frm,
              'breadcrumb'  => array(
                    "po umum"  => "mrp/mrp-po/list-po-umum"
                ),
              'css'         => $css,
              'foot'        => $foot,
//              'img'       => base_url()."themes/".DEFAULTTHEMES."/img/ajax-loader.gif"
//              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("main-mrp/detail-po-umum/view-detail");
    }
   
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
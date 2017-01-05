<thead>
    <tr>
        <th>Title</th>
        <th>telp</th>
        <th>fax</th>
        <th>Office</th>
        <th>Address</th>
        <th>Direktorat</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    foreach ($data as $key => $value) {
        
    $detail = $this->global_models->get_query("SELECT B.title,B.code"
    . " FROM hr_company_direktorat AS A"
    . " LEFT JOIN hr_master_organisasi AS B ON A.id_hr_master_organisasi = B.id_hr_master_organisasi"
    . " WHERE A.id_hr_company = '{$value->id_hr_company}'");
    $department = "";
    foreach ($detail as $val) {
            $department .= $val->code."<br>";
    }
      print '
      <tr>
        <td>'.$value->title.'</td>
        <td>'.$value->telp.'</td>
        <td>'.$value->fax.'</td>  
        <td>'.$value->office.'</td> 
        <td>'.$value->address.'</td>  
        <td>'.$department.'</td>     
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("hr/hr-master/add-company/".$value->id_hr_company).'">Edit</a></li>
              <li><a href="'.site_url("hr/hr-master/company-direktorat/".$value->id_hr_company).'">Direktorat</a></li>    
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
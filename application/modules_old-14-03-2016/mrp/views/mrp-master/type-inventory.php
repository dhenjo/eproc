
<thead>
    <tr>
        <th>Title</th>
        <th>Code</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    foreach ($data as $key => $value) {
        $status = array( 1=> "<span class='label bg-orange'>Active</span>", 2 => "<span class='label bg-maroon'>Non Active</span>");
      print '
      <tr>
        <td>'.$value->title.'</td>
        <td>'.$value->code.'</td>
        <td>'.$status[$value->status].'</td>    
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("mrp/mrp-master/add-type-inventory/".$value->id_mrp_type_inventory).'">Edit</a></li>
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
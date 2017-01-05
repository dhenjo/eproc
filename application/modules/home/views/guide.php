<div class="row">
  <div class="col-xs-9">
    
    <div class="box-body">
        <div class="box-group" id="accordion">
            <?php
            foreach($data AS $key => $dt){
            ?>
            <div class="box box-solid box-success">
                <div class="box-header">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#tab<?php print $key?>" style="color: white;">
                          <?php print $dt->title?>
                      </a>
                    </h4>
                </div>
                <div id="tab<?php print $key?>" class="panel-collapse collapse out">
                    <div class="box-body">
                        <?php print $dt->note;?>
                    </div>
                </div>
            </div>
          <?php
            }
          ?>
        </div>
    </div>
  </div>
</div>

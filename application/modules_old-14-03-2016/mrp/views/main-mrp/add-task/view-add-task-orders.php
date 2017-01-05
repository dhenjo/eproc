<div class="row">
  <div class="col-xs-12">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#task-orders" data-toggle="tab"><b>TASK ORDERS</b></a></li>
            <li><a href="#request-orders" id="request-orders2" data-toggle="tab"><b>REQUEST ORDERS</b></a></li>
            <li><a href="#purchase-orders" id="purchase-orders2" data-toggle="tab"><b>PURCHASE ORDERS</b></a></li>
            <!--<li class="pull-right"></li>-->
           
        </ul>
        <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="task-orders" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/add-task/view-task-orders');?>
            </div>
            <div class="chart tab-pane" id="request-orders" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/add-task/view-request-orders');?>
            </div>
            <div class="chart tab-pane" id="purchase-orders" style="position: relative; height: 300px;">
                <?php $this->load->view('main-mrp/add-task/view-po');?>
            </div>
        </div>
    </div>
  </div>
</div>
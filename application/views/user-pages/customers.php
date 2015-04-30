<?php    if($this->session->userdata('dbSuccess') != '') { ?>
        <div class="success-message">
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php 
                echo $this->session->userdata('dbSuccess');
                $this->session->set_userdata(array('dbSuccess'=>''));
                ?>
           </div>
       </div>
       <?php    } 

if(!isset($customer)){
$customer='';
}
if(!isset($customer_status_id)){
$customer_status_id='';
}

if(!isset($mobile)){
$mobile='';
}

if(!isset($trip_pick_date)){
$trip_pick_date='';
}
if(!isset($trip_drop_date)){
$trip_drop_date='';
}
?>

<div class="page-outer">    
	<fieldset class="body-border">
		<legend class="body-head">Customers</legend>
		
		<div class="box-body table-responsive no-padding">
			
			<form action="<?php echo base_url().'front-desk/customers'; ?>" method="get">
			<table class="table list-trip-table no-border">
				<tbody>
					<tr>
						<!--<td><?php echo form_input(array('name'=>'customer','id'=>'name','class'=>'customer form-control' ,'placeholder'=>'Customer name','value'=>$customer)); ?></td>-->
					    <td><?php echo form_input(array('name'=>'customer','id'=>'name','class'=>'customer form-control' ,'placeholder'=>'Customer Name','value'=>$customer)); ?></td>
					<td><?php echo form_input(array('name'=>'mobile','id'=>'mobile','class'=>'mobile form-control' ,'placeholder'=>'Mobile Number','value'=>$mobile)); ?></td>
						
					    <td><?php echo form_input(array('name'=>'trip_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'From Date','value'=>$trip_pick_date)); ?></td>
					    <td><?php  echo form_input(array('name'=>'trip_drop_date','class'=>'dropdatepicker initialize-date-picker form-control' ,'placeholder'=>'To Date','value'=>$trip_drop_date)); ?></td>
						
					    <td><?php echo form_submit("customer_search","Search","class='btn btn-primary'");
echo form_close();?></td>
						<td><?php echo form_open(  base_url().'front-desk/customer');
								  echo form_submit("add","Add","class='btn btn-primary'");
								  echo form_close(); 
						?></td>
						<!--<td>
						<?php echo form_open(  base_url().'customers/importToFa');
								  echo form_submit("Import","Import","class='btn btn-primary'");
								  echo form_close(); 
						?>
						</td>-->
						<td><div class="hide-me"><?php echo form_button('print-customer','Print',"class='btn btn-primary print-customer'"); ?></div></td>
						
					</tr>
				</tbody>
			</table>
		</div>
	
	<div class="msg"> <?php 
			if (isset($result)){ echo $result; } else {?></div>

	
		<div class="box-body table-responsive no-padding">
			<table class="table table-hover table-bordered table-with-20-percent-td">
				<tbody>
					<tr>	
						 
					    <th>Customer </th>
					    <th>Contact Details</th>
					    <th>Trip Count</th>	
						
						
						
					</tr>
					<?php
					
					for($customer_index=0;$customer_index<count($customers);$customer_index++) {
					?>
					<tr>
						
						<td><?php echo anchor(base_url().'front-desk/customer/'.$customers[$customer_index]['id'],$customers[$customer_index]['name']).br();?></td>
					    <td><?php echo $customers[$customer_index]['mobile'].br();?>
						<?php echo $customers[$customer_index]['address']; ?>
						</td>

					    <td><?php echo $customers[$customer_index]['tripcount']; ?></td>
					    

					</tr>
					<?php 
						}
					?>
				</tbody>
			</table><?php echo $page_links;?>
		</div>
		<?php } ?>
	</fieldset>
</div>


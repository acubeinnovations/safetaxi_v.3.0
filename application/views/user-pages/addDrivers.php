 <?php
	
 $driver_id=gINVALID;
	$name='';
	$place_of_birth='';
	$dob='';
	$blood_group='';
	$marital_status_id='';
	$children='';
	$present_address='';
	$address='';
	$district='';
	$state='';
	$pin_code='';
	$phone='';
	$mobile='';
	$email='';
	$date_of_joining='';
	$badge='';
	$license_number='';
	$license_renewal_date='';
	$badge_renewal_date='';
	$mother_tongue='';
	$pan_number='';
	$bank_account_number='';
	$name_on_bank_pass_book='';
	$bank_name='';
	$branch='';
	$bank_account_type_id='';
	$ifsc_code='';
	$id_proof_type_id='';
	$id_proof_document_number='';
	$name_on_id_proof='';
	$vehicle_registration_number='';
	$device_imei='';
	$device_sim_number='';
	$driver_status_id='';
	//$app_key='';
	$base_location='';
	//$status_description='';
	

 if($this->mysession->get('post')!=null){
 //echo $result[''];
 $data=$this->mysession->get('post');
	$driver_id=$this->mysession->get('driver_id');
	$name=$data['name'];
	$dob=$data['dob'];
	$blood_group=$data['blood_group'];
	$permanent_address=$data['address'];
	$district=$data['district'];
	$state=$data['state'];
	$pin_code=$data['pin_code'];
	$mobile=$data['mobile'];
	$email=$data['email'];
	$vehicle_registration_number=$data['vehicle_registration_number'];
	$device_imei=$data['device_imei'];
	$device_sim_number=$data['device_sim_number'];
	$app_key=$data['app_key'];
	$base_location=$data['base_location'];
	$driver_status_id=$data['driver_status_id'];
	//$status_description=$data['status_description'];

$this->mysession->delete('post');
}
 else if(isset($result)&&$result!=null){ 
    $driver_id=$result['id'];
	$name=$result['name'];
	$dob=$result['dob'];
	$blood_group=$result['blood_group'];
	$address=$result['address'];
	$district=$result['district'];
	$state=$result['state'];
	$pin_code=$result['pin_code'];
	$mobile=$result['mobile'];
	$email=$result['email'];
	$vehicle_registration_number=$result['vehicle_registration_number'];
	$device_imei=$result['device_imei'];
	$device_sim_number=$result['device_sim_number'];
	$app_key=$result['app_key'];
	$base_location=$result['base_location'];
	$driver_status_id=$result['driver_status_id'];
	//$status_description=$result['status_description'];

} 
?>
<?php if($this->session->userdata('dbSuccess') != '') { ?>
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
       <?php    } ?>
		<div class="page-outer">
	   <fieldset class="body-border">
		<legend class="body-head">Manage Drivers</legend>
	<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li><a href="#tab_1" data-toggle="tab">Profile</a></li>

    </ul>
    <div class="tab-content">
        
        <div class="tab-pane active" id="tab_1">

       
			 <div class="width-30-percent-with-margin-left-20-Driver-View">

<fieldset class="body-border-Driver-View border-style-Driver-view" >
<legend class="body-head">Personal Details</legend>

		<?php  echo form_open(base_url()."driver/driver_manage");?>
        <div class="form-group">
		<?php echo form_label('Enter Name','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'driver_name','class'=>'form-control','id'=>'name','placeholder'=>'Enter Name','value'=>$name)); ?>
	   <?php echo $this->form_functions->form_error_session('driver_name', '<p class="text-red">', '</p>'); ?>
        </div>

	
	
	<div class="form-group">
	<?php echo form_label('Permanent Address','usernamelabel'); ?>
           <?php echo form_textarea(array('name'=>'address','class'=>'form-control','id'=>'address','placeholder'=>'Permanent Address','value'=>$address,'rows'=>5)); ?>
	   <?php echo $this->form_functions->form_error_session('address', '<p class="text-red">', '</p>'); ?>
        </div>
	<div class="form-group">
	<?php echo form_label('District','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'district','class'=>'form-control','id'=>'district','placeholder'=>'District','value'=>$district)); ?>
	   <?php echo $this->form_functions->form_error_session('district', '<p class="text-red">', '</p>'); ?>
        </div>
	

	<div class="form-group">
	<?php echo form_label('Mobile','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'mobile','class'=>'form-control','id'=>'drivermobile','placeholder'=>'Mobile','value'=>$mobile)); ?>
	   <?php echo $this->form_functions->form_error_session('mobile', '<p class="text-red">', '</p>'); ?>
       <div class="hide-me" > <?php echo form_input(array('name'=>"hmob",'value'=>$mobile));?></div>
		</div>
	<div class="form-group">
	<?php echo form_label('E-mail ID','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'email','class'=>'form-control','id'=>'driveremail','placeholder'=>'E-mail ID','value'=>$email)); ?>
	   <?php echo $this->form_functions->form_error_session('email', '<p class="text-red">', '</p>'); ?>
	 <div class="hide-me" >  <?php echo form_input(array('name'=>"hmail",'value'=>$email));?></div>
        </div>

		</fieldset> </div>
		
	<div class="width-30-percent-with-margin-left-20-Driver-View">
<fieldset class="body-border-Driver-View border-style-Driver-view" >
<legend class="body-head">Other Details</legend>

	<div class="form-group">
	<?php echo form_label('Vehicle Registration Number','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'vehicle_registration_number','class'=>'form-control','id'=>'vehicle_registration_number','placeholder'=>'Vehicle Registration Number','value'=>$vehicle_registration_number)); ?>
	<?php echo $this->form_functions->form_error_session('vehicle_registration_number', '<p class="text-red">', '</p>'); ?>
    </div>

    <div class="form-group">
	<?php echo form_label('Device IMEI','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'device_imei','class'=>'form-control','id'=>'device_imei','placeholder'=>'Device IMEI','value'=>$device_imei)); ?>
	<?php echo $this->form_functions->form_error_session('device_imei', '<p class="text-red">', '</p>'); ?>
    </div>

    

    <div class="form-group">
	<?php echo form_label('App Key','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'app_key','class'=>'form-control','id'=>'app_key','placeholder'=>'App Key','value'=>$app_key,'readonly'=>true)); ?>
	
    </div>

    
	<?php if(isset($driver_id) && $driver_id!=gINVALID) { ?>
				<div class="form-group">
				<?php 
				 $class="form-control";
				 $selected=$driver_status_id;
					echo form_label('Status','statuslabel');
				 echo $this->form_functions->populate_dropdown('driver_status_id',$select['driver_statuses'],$selected,$class);
				 ?>
				</div>
				<?php }  ?>

    <!--<div class="form-group">
	<?php //echo form_label('Status Description','usernamelabel'); ?>
           <?php //echo form_input(array('name'=>'status_description','class'=>'form-control','id'=>'status_description','placeholder'=>'Status Description','value'=>$status_description)); ?>
	<?php //echo $this->form_functions->form_error_session('status_description', '<p class="text-red">', '</p>'); ?>
    </div>-->


	





	
		<div class='hide-me'><?php  
		echo form_input(array('name'=>'hidden_id','class'=>'form-control','value'=>$driver_id));?></div>
   		<div class="box-footer">
		
		<?php echo br();
		 if($driver_id==gINVALID||$driver_id==''){
			$btn_name='Save';
		 }else {
			$btn_name='Update';
			}
		echo form_submit("driver-submit",$btn_name,"class='btn btn-primary'"); ?>  
        </div>
	 <?php echo form_close(); ?>
	</fieldset>


</div>	
        </div>
		
</div>	


</fieldset>
</div>

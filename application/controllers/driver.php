<?php 
class Driver extends CI_Controller {
	public function __construct()
		{
		parent::__construct();
		$this->load->model("driver_model");
		$this->load->model('account_model');
		$this->load->helper('my_helper');
		$this->load->model('driver_payment_model');
		$this->load->model("notification_model");
		$this->load->model("trip_booking_model");
		no_cache();

		}
		public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
	} else {
		return false;
	}
	}
	//for driver view display

	public function driver_manage(){
	if($this->session_check()==true) {
	if(isset($_REQUEST['driver-submit'])){
	$data['name']=$this->input->post('driver_name');
	
	$data['dob']=$this->input->post('dob');
	$data['blood_group']=$this->input->post('blood_group'); 
	//$data['present_address']=$this->input->post('present_address');
	$data['address']=$this->input->post('address');
	$data['district']=$this->input->post('district');
	$data['state']=$this->input->post('state');
	$data['pin_code']=$this->input->post('pin_code');
	$data['mobile']=$this->input->post('mobile');
	$hmob=$this->input->post('hmob'); 
	$data['email']=$this->input->post('email');
	
	$hmail=$this->input->post('hmail');

	$dr_id=$this->input->post('hidden_id');
	



	$data['vehicle_registration_number']=$this->input->post('vehicle_registration_number');
	$data['device_imei']=$this->input->post('device_imei');
	$data['device_sim_number']=$this->input->post('device_sim_number');
	$data['app_key']=$this->input->post('app_key');
	$data['base_location']=$this->input->post('base_location');
	//$data['status_description']=$this->input->post('status_description');


	$data['user_id']=$this->session->userdata('id');

		$err=True;
	 $this->form_validation->set_rules('driver_name','Name','trim|required|xss_clean');
	
	 
	 $this->form_validation->set_rules('address','Permanent Address','trim|xss_clean');
	 $this->form_validation->set_rules('district','District','trim|xss_clean|alpha');
	 //$this->form_validation->set_rules('state','State','trim|xss_clean');
	 //$this->form_validation->set_rules('pin_code','Pin Code','trim|xss_clean|regex_match[/^[0-9]{6}$/]');
	 $this->form_validation->set_rules('mobile','Phone Number','trim|required|xss_clean|numeric]');
	 if ($dr_id==gINVALID) {
			$data['driver_status_id']='1';
		 $this->form_validation->set_rules('email','Email','trim|xss_clean|valid_email|is_unique[drivers.email]');
	}elseif($dr_id!=gINVALID){
		$data['driver_status_id']=$this->input->post('driver_status_id');
		if($hmail!=$data['email']){
			 $this->form_validation->set_rules('email','Email','trim|xss_clean|valid_email|is_unique[drivers.email]');
			}else{
				 $this->form_validation->set_rules('email','Email','trim|xss_clean|valid_email');
			}
	}

	


	 $this->form_validation->set_rules('vehicle_registration_number','Vehicle Registration Number','trim|required|xss_clean');
	 $this->form_validation->set_rules('device_imei','Vehicle Device IMEI','trim|required|xss_clean');
	 //$this->form_validation->set_rules('device_sim_number','Device Sim Number','trim|required|xss_clean');
	 $this->form_validation->set_rules('app_key','App Key','trim|required|xss_clean');
	 //$this->form_validation->set_rules('base_location','Base Location','trim|required|xss_clean');
	 //$this->form_validation->set_rules('status_description','Status Description','trim|required|xss_clean');

	//echo "<pre>"; print_r($data); echo "</pre>"; exit();
	
		
	 if($this->form_validation->run()==False|| $err==False){
		$this->mysession->set('driver_id',$dr_id);
		$this->mysession->set('post',$data); 
		redirect(base_url().'front-desk/driver-profile',$data);	
	 }
	 else{
	
		if($dr_id==gINVALID ){
			$res=$this->driver_model->addDriverdetails($data); 
			//print_r($res);
			//$ins_id=$this->mysession->get('vehicle_id');
			if($res>0){

				//save customer in fa table
						//==========================add customer==============
						$debtor = array('custname' =>$data['name'],//customer full name
								'cust_ref' =>$data['name'],//customer short name)
								'address' =>$data['address'],// customer address
								'phone' => $data['mobile'],//customer phone
								'phone2' => $data['mobile'],//customer phone2
								'email' => $data['email']
								);

						$method = isset($_GET['m']) ? $_GET['m'] : 'p';
						$action = isset($_GET['a']) ? $_GET['a'] : 'customers';
						$record = isset($_GET['r']) ? $_GET['r'] : '';
						$filter = isset($_GET['f']) ? $_GET['f'] : false;
						$fa_customer = $this->fabridge->open($method, $action, $record, $filter,$debtor);
//print_r($fa_customer);exit;
						if($fa_customer){
							$updtdata['fa_customer_id']== $fa_customer['debtor_no'];
							$this->driver_model->UpdateDriverdetails($updtdata,$res);
							//save this id in customers table- fa_customer_id
						}

						//======================================================

				$this->session->set_userdata(array('dbSuccess'=>' Added Succesfully..!'));
				$this->session->set_userdata(array('dbError'=>''));
				redirect(base_url().'front-desk/driver-profile');
			}
		}
		else{


			
			$res=$this->driver_model->UpdateDriverdetails($data,$dr_id);
			
			if($res==true){
				//==========================edit customer==============
						$debtor = array('custname' =>$data['name'],//customer full name
								'cust_ref' =>$data['name'],//customer short name)
								'address' =>$data['address']// customer address
								);

						$method = isset($_GET['m']) ? $_GET['m'] : 't';
						$action = isset($_GET['a']) ? $_GET['a'] : 'customers';
						$record = isset($_GET['r']) ? $_GET['r'] : '';
						$filter = isset($_GET['f']) ? $_GET['f'] : false;
						$update = $this->fabridge->open($method, $action, $record, $filter,$debtor);

						//======================================================

				$this->session->set_userdata(array('dbSuccess'=>' Updated Succesfully..!'));
				$this->session->set_userdata(array('dbError'=>''));
				redirect(base_url().'front-desk/driver-profile/'.$dr_id);
			}
		}
	
	 }
	
	
	}
	
	}
	else{
			$this->notAuthorized();
			}
	}
	
	
	
	
	public function load_templates($page='',$data=''){
	if($this->session_check()==true) {
		$this->load->view('admin-templates/header',$data);
		$this->load->view('admin-templates/nav');
		$this->load->view($page,$data);
		$this->load->view('admin-templates/footer');
		}
	else{
			$this->notAuthorized();
		}
	}
	public function notAuthorized(){
	$data['title']='Not Authorized | '.PRODUCT_NAME;
	$page='not_authorized';
	$this->load->view('admin-templates/header',$data);
	$this->load->view('admin-templates/nav');
	$this->load->view($page,$data);
	$this->load->view('admin-templates/footer');
	
	}	

	public function date_check($date){
	if( strtotime($date) >= strtotime(date('Y-m-d')) ){
	return true;
	}	
	}



	public function DriverPayments(){
	
	if(isset($_REQUEST['payment-submit'])){

	//Newly Added	
	//$res=$this->driver_payment_model->getAllDriverpayment();
	//print_r($res);
	/*for($index=0; $index<count($res);$index++){
		$id=$res[$index]['id'];
		$voucher_type_id=$res[$index]['year'];
		
		$month = explode('-', $this->input->post('payment_date'));
		$data['period']=$month[1];
		if($data['period']==$month){
			echo "same month"; 
		} else{
			echo "not same";
		}

	} */	//



	//Newly Added ends
	$data['voucher_type_id']=$this->input->post('payment_type'); 
	if($this->input->post('payment_type')==RECEIPT){
		$data['cr_amount']=$this->input->post('amount');
		$data['dr_amount']=0;
		$data['voucher_number']="RECEIPT";
	}elseif ($this->input->post('payment_type')==PAYMENT){
		$data['cr_amount']=$this->input->post('amount');
		$data['voucher_number']="PYMNT";
		$data['dr_amount']=0;
	}elseif ($this->input->post('payment_type')==INVOICE){
		$data['dr_amount']=$this->input->post('amount');
		$data['cr_amount']=0;
		
	}	
	
	}//for loop
	
	 $data['payment_date']=date('Y-m-d',strtotime($this->input->post('payment_date')));
	$data['driver_id']=$this->input->post('driver_id'); 
	$payment_id=$this->input->post('payment_id');
	$year = explode('-', $data['payment_date']);
	$data['year']=$year[0];
	$month = explode('-', $data['payment_date']);
	$data['period']=$month[1];
	if($payment_id!=gINVALID){
		$res=$this->driver_payment_model->editDriverpayment($data,$payment_id); 
		$action='Updated';
	}else{
		$res=$this->driver_payment_model->addDriverpayment($data); 
		$action='Added';
	}
	if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>$action.' Succesfully..!'));
		$this->session->set_userdata(array('dbError'=>''));
		redirect(base_url().'front-desk/driver-payments/'.$data['driver_id']);
	}
	else{
		$this->session->set_userdata(array('dbSuccess'=>''));
		$this->session->set_userdata(array('dbError'=>$action.' Unsuccesfully..!'));
		redirect(base_url().'front-desk/driver-payments/'.$data['driver_id']);
	}
	
}

	public function sendNotification(){
		if(isset($_REQUEST['send-notification'])){

			$driver_id=$_REQUEST['driver'];
			$message=$_REQUEST['message'];
			$pdata['driver']=$_REQUEST['driver'];
			$data['message']=$pdata['message']=$_REQUEST['message'];
		
			 $this->form_validation->set_rules('message','Message','trim|xss_clean|required');
			if($this->form_validation->run()==False){
			$this->mysession->set('post',$pdata); 
			redirect(base_url().'front-desk/sendNotifications');	

			}else{
			if($driver_id!=gINVALID){
				$condition=array('id'=>$driver_id);
			}else{
				$condition='';
			}
			$drivers=$this->driver_model->getDriversAppKey($condition);
			$data['notification_type_id']=NOTIFICATION_TYPE_COMMON_MSGS;
			$data['notification_status_id']=NOTIFICATION_STATUS_NOTIFIED;
			$data['notification_view_status_id']=NOTIFICATION_VIEWED_STATUS;
			$this->driver_model->sendNotification($data,$drivers);
			


			$response['td']=TD_COMMON_MSGS;
			$response['cmsg']='"'.$data['message'].'"';
		
			
			for($driver_index=0;$driver_index<count($drivers);$driver_index++){
					$app_key=$drivers[$driver_index];
					
					$gcm=$this->trip_booking_model->getDriverGcmRegId($app_key);
					if(isset($gcm[0]['gcm_regid'])){
					$this->gcm->send_notification($gcm[0]['gcm_regid'], $response);
					}
				}

			redirect(base_url().'front-desk/sendNotifications');

			}

		}

	}

		

}

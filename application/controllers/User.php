<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	public function offers()
	{	
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Method must be GET'));
		} else {
			$params = json_decode(file_get_contents('php://input'), TRUE);
			$params['user_id'] = $this->MyModel->verify_token($params);
			if (!$params['user_id']) {
				return;
			}
			$this->load->model('UserModel');
			$response = $this->UserModel->offers();
			json_output($response['status'],$response);
		}
	}
	public function addAddress()
	{	
		$method = $_SERVER['REQUEST_METHOD'];
		$params = json_decode(file_get_contents('php://input'), TRUE);
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		}else {
			$params['user_id'] = $this->MyModel->verify_token($params);
			if (!$params['user_id']) {
				return;
			}
			$params['full_name']= empty($params['full_name'])?'':$params['full_name'];
			$params['contact']= empty($params['contact'])?'':$params['contact'];
			$params['address']= empty($params['address'])?'':$params['address'];
			$params['city']= empty($params['city'])?'':$params['city'];
			$params['province']= empty($params['province'])?'':$params['province'];
			$params['label']= empty($params['label'])?'':$params['label'];
			if(empty($params['full_name']) || empty($params['contact']) || empty($params['address']) || empty($params['city']) || empty($params['province']) || empty($params['label'])){
				return json_output(400,array('status' => 400,'message' => "All fields are required"));
			}
			$this->load->model('UserModel');
			$response = $this->UserModel->addCustomerAddress($params);
			json_output($response['status'],$response);
		}
	}
	public function customerAddress()
	{	
		$method = $_SERVER['REQUEST_METHOD'];
		$action  = $this->input->get('action', TRUE);
		$params = json_decode(file_get_contents('php://input'), TRUE);
		if($action && $action=='get'){
			if($method != 'GET'){
				json_output(400,array('status' => 400,'message' => 'Method for request should be GET'));
			} else {
				$params['user_id'] = $this->MyModel->verify_token($params);
				if (!$params['user_id']) {
					return;
				}
				$this->load->model('UserModel');
				$response = $this->UserModel->getCustomerAddresses($params);
				json_output($response['status'],$response);
			}
		}if($action && $action=='update'){
			if($method != 'PUT'){
				json_output(400,array('status' => 400,'message' => 'Method for request should be PUT'));
			} else {
				$params['user_id'] = $this->MyModel->verify_token($params);
				if (!$params['user_id']) {
					return;
				}
				$this->load->model('UserModel');
				//return json_output(400,array('status' => 400,'message' => $params));
				$response = $this->UserModel->updateCustomerAddressLabel($params);
				json_output($response['status'],$response);
			}
		}elseif($action!="update" && $action!="get"){
			return json_output(400,array('status' => 400,'message' => 'Invalid action.'));
		}elseif($action==""){
			return json_output(400,array('status' => 400,'message' => 'Action is required'));
		}
	}
	
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function getSliderImages()
	{	
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Method must be GET'));
		} else {
			$params = json_decode(file_get_contents('php://input'), TRUE);
			$this->load->model('ProductModel');
			$response = $this->ProductModel->getSliderImages();
			json_output($response['status'],$response);
		}
	}
	public function offers()
	{	
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Method must be GET'));
		} else {
			$params = json_decode(file_get_contents('php://input'), TRUE);
			// $params['user_id'] = $this->MyModel->verify_token($params);
			// if (!$params['user_id']) {
			// 	return;
			// }
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
			$params['customer_id'] = $this->MyModel->verify_token($params);
			if (!$params['customer_id']) {
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
		}elseif($action && $action=='update'){
			if($method != 'PUT'){
				json_output(400,array('status' => 400,'message' => 'Method for request should be PUT'));
			} else {
				$params['customer_id'] = $this->MyModel->verify_token($params);
				if (!$params['customer_id']) {
					return;
				}
				$this->load->model('UserModel');
				//return json_output(400,array('status' => 400,'message' => $params));
				$response = $this->UserModel->updateCustomerAddressLabel($params);
				json_output($response['status'],$response);
			}
		}elseif($action && $action=='delete'){
		    if($method != 'DELETE'){
				json_output(400,array('status' => 400,'message' => 'Method for request should be DELETE'));
			} else {
				$params['customer_id'] = $this->MyModel->verify_token($params);
				if (!$params['customer_id']) {
					return;
				}
				$params['address_id']= empty($params['address_id'])?'':$params['address_id'];
				if(empty($params['address_id'])){
				    return json_output(400,array('status' => 400,'message' => 'address_id is required'));
				}
				$this->load->model('UserModel');
				//return json_output(400,array('status' => 400,'message' => $params));
				$response = $this->UserModel->deleteCustomerAddress($params);
				json_output($response['status'],$response);
			}
		}elseif($action!="update" && $action!="get"){
			return json_output(400,array('status' => 400,'message' => 'Invalid action.'));
		}elseif($action==""){
			return json_output(400,array('status' => 400,'message' => 'Action is required'));
		}
	}
	public function products()
	{	
		$method = $_SERVER['REQUEST_METHOD'];
		//return json_output(400,array('status' => 400,'message' => $method));
		$action  = $this->input->get('action', TRUE);
		$params = json_decode(file_get_contents('php://input'), TRUE);
		if($action && $action=='get'){
			if($method != 'GET'){
				json_output(400,array('status' => 400,'message' => 'Method for request should be GET'));
			} else {
				// $params['user_id'] = $this->MyModel->verify_token($params);
				// if (!$params['user_id']) {
				// 	return;
				// }
				$this->load->model('ProductModel');
				$response = $this->ProductModel->getAllProducts($params);
				json_output($response['status'],$response);
			}
		}else if($action && $action=='getCart'){
			if($method != 'GET'){
				json_output(400,array('status' => 400,'message' => 'Method for request should be GET'));
			} else {
				$params['user_id'] = $this->MyModel->verify_token($params);
				if (!$params['user_id']) {
					return;
				}
				$this->load->model('ProductModel');
				$response = $this->ProductModel->getCart($params);
				json_output($response['status'],$response);
			}
		}else if($action && $action=='update'){
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
		}else if($action && $action=='deleteCartItem'){
			if($method != "DELETE"){
				json_output(400,array('status' => 400,'message' => 'Method for request should be DELETE'));
			} else {
				$params['user_id'] = $this->MyModel->verify_token($params);
				if (!$params['user_id']) {
					return;
				}
				$params['product_id']= empty($params['product_id'])?'':$params['product_id'];
				if(empty($params['product_id'])){
					return json_output(400,array('status' => 400,'message' => "product_id is required"));
				}
				$this->load->model('ProductModel');
				$response = $this->ProductModel->deleteCartItem($params);
				json_output($response['status'],$response);
			}
		}else if($action==""){
			if($method != 'POST'){
				json_output(400,array('status' => 400,'message' => 'Method for request should be POST'));
			} else {
				$params['user_id'] = $this->MyModel->verify_token($params);
				if (!$params['user_id']) {
					return;
				}
				$this->load->model('ProductModel');
				$response = $this->ProductModel->addToCart($params);
				json_output($response['status'],$response);
			}
		}
	}
	
}

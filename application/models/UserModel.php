<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

    public function offers()
    {
        $offers = $this->db->select('*')->from('sab_offers')->get()->result_array();
        if($offers){
			return array('status' => 200,'message' => "Offers",'total ofers '=>count($offers),'data'=>$offers);
        }else{
            return array('status' => 400,'message' => "Offers not availble");
        } 
    }
    public function addCustomerAddress($data)
    { 
        if(count($data) ==1){
            return array('status' => 400,'message' => 'Data is required.');
        }else{
            $data['customer_city'] = $data['city'];
            $data['customer_status'] = 1;
            unset($data['city']);
            //return array('status' => 400,'message' => $data);
            $result  = $this->db->insert('sab_customer_addresses',$data);
            if($result){
                return array('status' => 200,'message' => 'Address added Successfully');
            }else{
                return array('status' => 400,'message' => 'Address Not added,try again');
            }
        } 
    }
    public function getCustomerAddresses($data)
    { 
        if(count($data) ==1 && $data['user_id']){
            $customer_addresses = $this->db->select('*')->from('sab_customer_addresses')->where(['customer_id'=>$data['user_id'],"customer_status"=>'1'])->get()->result_array();
            if($customer_addresses){
                return array('status' => 200,'message' => 'Customer Addresses','Tolat Customer Addresses'=>count($customer_addresses),'data'=>$customer_addresses);
            }else{
                return array('status' => 400,'message' => 'Address Not added,try again');
            }
        }else{
            return array('status' => 400,'message' => "Address Not availble");
        } 
    }
    public function updateCustomerAddressLabel($data)
    {   //return array('status' => 200,'message' => $data);
        $required = ['label','address_id'];
        if (count($data)==1) { return array('status' => 400,'message' =>"label and address_id is required"); } 
        foreach($required as $column){
            if (!array_key_exists($column, $data)) {
                return array('status' => 400,'message' => "$column field is required");
            } else if(trim($data[$column]) === '') {
                return array('status' => 400,'message' => "$column can not be empty");
            }
        }
        $customer_id = $data['customer_id'];
        $address_id = $data['address_id'];
        unset($data['address_id']);
        $this->db->set($data);
        $this->db->where(['id'=>$address_id,'customer_id'=>$customer_id]);
        $result = $this->db->update('sab_customer_addresses'); 
        if($result){
            return array('status' => 200,'message' => "Data has been updated");
        }else{
            return array('status' => 400,'message' => "Data not Updated");
        }
    }
    public function deleteCustomerAddress($data)
    { //return array('status' => 200,'message' => $data);
        $this->db->set('customer_status','0');
        $this->db->where('id',$data['address_id']);
        $result = $this->db->update('sab_customer_addresses'); 
        if($result){
            // $customer_addresses = $this->db->select('*')->from('sab_customer_addresses')->where(['customer_id'=>$data['customer_id'],"customer_status"=>'1'])->get()->result_array();
            // if($customer_addresses){
            //     return array('status' => 200,'message' => 'Customer Addresses','Tolat Customer Addresses'=>count($customer_addresses),'data'=>$customer_addresses);
            // }else{
            //     return array('status' => 400,'message' => 'Something Went Wrong,try again');
            // }
            return array('status' => 200,'message' => "Customer Address has been Deleted");
        }else{
            return array('status' => 400,'message' => "Customer Address not Deleted");
        }
    }
}

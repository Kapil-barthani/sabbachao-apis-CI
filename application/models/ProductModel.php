<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductModel extends CI_Model {

    public function getAllProducts($data)
    { 
        if(count($data) ==1 && $data['user_id']){
            $all_products = $this->db->select('*')->from('products')->get()->result_array();
            if($all_products){
                return array('status' => 200,'message' => 'Products','Tolat Products'=>count($all_products),'products'=>$all_products);
            }else{
                return array('status' => 400,'message' => 'something went wrong');
            }
        }else{
            return array('status' => 400,'message' => "Products Not availble");
        } 
    }
    public function addToCart($data)
    {   
        $required = ['product_id','quantity','total_price'];
        if (count($data)==1) { return array('status' => 400,'required fields' =>$required); } 
        foreach($required as $column){
            if (!array_key_exists($column, $data)) {
                return array('status' => 400,'message' => "$column field is required");
            } else if(trim($data[$column]) === '') {
                return array('status' => 400,'message' => "$column can not be empty");
            }
        }
        if($data['user_id']){
            $cart_id = $this->db->select('*')->from('carts')->where(['user_id'=>$data['user_id'],'status'=>1])->get()->row();
            if($cart_id){
                $cart_item_result_id = $this->db->select('*')->from('cart_items')->where(['cart_id'=>$cart_id->cart_id,'user_id'=>$data['user_id'],'product_id'=>$data['product_id'],'status'=>1])->get()->row();
                if($cart_item_result_id->cart_item_id){
                    //return array('status' => 200,'message' => $cart_item_result_id->cart_item_id);
                    $cart_item_result = $this->db->where('cart_item_id',$cart_item_result_id->cart_item_id)->update('cart_items',['quantity'=>$data['quantity'],'total_price'=>$data['total_price']]);
                    if($cart_item_result){
                        return array('status' => 200,'message' => "Item updated successfully");
                    }else{
                        return array('status' => 400,'message' => "Item not updated please try again");
                    }
                }else{
                    $cart_item_result  = $this->db->insert('cart_items',['cart_id'=>$cart_id->cart_id,'user_id'=>$cart_id->user_id,'product_id'=>$data['product_id'],'quantity'=>$data['quantity'],'total_price'=>$data['total_price']]);
                    if($cart_item_result){
                        return array('status' => 200,'message' => "Item in cart added successfully");
                    }else{
                        return array('status' => 400,'message' => "Item not added please try again");
                    }
                }
            }else{
                $cart_result  = $this->db->insert('carts',['user_id'=>$data['user_id']]);
                $cart_id = $this->db->insert_id();
                if($cart_result){
                    $cart_item_result_id = $this->db->select('*')->from('cart_items')->where(['cart_id'=>$cart_id,'user_id'=>$data['user_id'],'product_id'=>$data['product_id'],'status'=>1])->get()->row();
                    if($cart_item_result_id->cart_item_id){
                        $cart_item_result = $this->db->where('cart_item_id',$cart_item_result_id->cart_item_id)->update('cart_items',['quantity'=>$data['quantity'],'total_price'=>$data['total_price']]);
                        if($cart_item_result){
                            return array('status' => 200,'message' => "Item updated successfully");
                        }else{
                            return array('status' => 400,'message' => "Item not updated please try again");
                        }
                    }else{
                        $cart_item_result  = $this->db->insert('cart_items',['cart_id'=>$cart_id,'user_id'=>$data['user_id'],'product_id'=>$data['product_id'],'quantity'=>$data['quantity'],'total_price'=>$data['total_price']]);
                        if($cart_item_result){
                            return array('status' => 200,'message' => "Item in cart added successfully");
                        }else{
                            return array('status' => 400,'message' => "Item not added please try again");
                        }
                    }
                }else{
                    return array('status' => 400,'message' => "Product not added please try again");
                }
            }
        }else{
            return array('status' => 400,'message' => "something went wrong");
        } 
    }
    
}

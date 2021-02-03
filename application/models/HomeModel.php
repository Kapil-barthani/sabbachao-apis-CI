<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeModel extends CI_Model {

    public function NearByStores($params){
        $longitude = $params['longitude'];
        $latitude = $params['latitude'];
        
        //$this->db->query("update sab_company_stores set home_delivary='1',pick_yourself='1',express_delivary='0',min_price='300',max_price='500',owner_name='Muhammad Salam',owner_contact='03312477922',store_image='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSo56jPdiqXhXrNfGd0xHqrjR_m95tRN3bK_g&usqp=CAU' where id = '34'");
        
        $meta = [];
        $data = $this->db->select('id,name,address,home_delivary,pick_yourself,express_delivary,min_price,max_price,owner_name,owner_contact,store_ratings,store_image,latitude,longitude')->from('sab_company_stores')->order_by('id','desc')->get()->result_array();
        //return array('status' => 200,'data'=>$data);
        foreach($data as $key=>$value){
            $d = getDistanceBetweenPointsNew($value["latitude"], $value["longitude"], $latitude, $longitude);
            if(($d*1000) <= 300)
                $meta[] = $value;
        }
        return array('status' => 200,'message' => 'Total Stores: '.count($meta),'data'=>$meta);
    }  
}
// Outside the Class
/**
 * Method to find the distance between 2 locations from its coordinates.
 * 
 * @param latitude1 LAT from point A
 * @param longitude1 LNG from point A
 * @param latitude2 LAT from point A
 * @param longitude2 LNG from point A
 * 
 * @return Float Distance in Kilometers.
 */
function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Km') {
    $theta = $longitude1 - $longitude2;
    $distance = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));

    $distance = acos($distance); 
    $distance = rad2deg($distance); 
    $distance = $distance * 60 * 1.1515;

    switch($unit) 
    { 
        case 'Mi': break;
        case 'Km' : $distance = $distance * 1.609344; 
    }

    return (round($distance,2)); 
}

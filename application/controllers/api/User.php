<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class User extends \Restserver\Libraries\REST_Controller
{
    public function __construct()
    {
        parent::__construct($config = 'rest');
        // Load User Model
        // $this->load->model('App_model', 'UserModel');
        // header("Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE");
        // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Content-Type: application/x-www-form-urlencoded');
        $this->load->helper('url');
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');



        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            die();
        }
        $this->load->library('form_validation');
    }

    /**
     * User Register
     * --------------------------
     * @param: user_name
     * @param: username
     * @param: email
     * @param: password
     * --------------------------
     * @method : POST
     * @link : api/user/register
     */


    public function productDetailsByLimit_get()
    {
       
        header("Access-Control-Allow-Origin: *");
        $location=$this->input->get('location');
        $city=$this->input->get('city');
       
        $category=$this->input->get('category');
        // $city=$this->input->get('city');
        $perPage=$this->input->get('page');
        
        $limit=10;
        $skip=0;
        if($perPage){
         $skip=($perPage-1)*$limit;
        }
        
        $count= $this->Fetch_Model->getProductsCount($location)->num_rows();
        
        $page=ceil($count/$limit);
        $allData=$this->Fetch_Model->productListAllData()->result_array();
        $data=$this->Fetch_Model->productListByLimit($limit,$skip,$location,$category)->result_array();  
         $products=array();
         foreach($data as $row){
             $row['featureImages'] = $this->Fetch_Model->getFeatureImages($row['prodId']);
             array_push($products, $row);
         }
         
         $banner=$this->db->get('banner')->result_array();
         $filter=$this->db->get('cat')->result_array();
        
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'productdetails', 'data' => $products,'count'=>$page,'banner'=>$banner,'filter'=>$filter];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
    
        public function productLocationFilter_get()
    {
       
        header("Access-Control-Allow-Origin: *");
        $location=$this->input->get('location');
        $perPage=$this->input->get('page');
        $limit=10;
        $skip=0;
        if($perPage){
         $skip=($perPage-1)*$limit;
        }
        $count= $this->Fetch_Model->getProductsCount();
        $data=$this->Fetch_Model->productLocFilterListByLimit($limit,$skip,$location)->result_array();  
         $products=array();
         foreach($data as $row){
             $row['featureImages'] = $this->Fetch_Model->getFeatureImages($row['prodId']);
             array_push($products, $row);
         }
         
         $banner=$this->db->get('banner')->result_array();
         $filter=$this->db->get('cat')->result_array();
        
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'productdetails', 'data' => $products,'count'=>$count,'banner'=>$banner,'filter'=>$filter];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
    public function productCategoryDetails_get()
    {
       
        header("Access-Control-Allow-Origin: *");
        $category=$this->input->get('category');
        $perPage=$this->input->get('page');
        $limit=10;
        $skip=0;
        if($perPage){
         $skip=($perPage-1)*$limit;
        }
        $location=$this->input->get('location');
        $count= $this->Fetch_Model->productCatFilterCount($location,$category)->num_rows();
        $page=ceil($count/$limit);
        $data=$this->Fetch_Model->productCatFilterListByLimit($limit,$skip,$location,$category)->result_array();  
         $products=array();
         foreach($data as $row){
             $row['featureImages'] = $this->Fetch_Model->getFeatureImages($row['prodId']);
             array_push($products, $row);
         }
         $banner=$this->db->get('banner')->result_array();
         $filter=$this->db->get('cat')->result_array();
        
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'productdetails', 'data' => $products,'count'=>$page,'banner'=>$banner,'filter'=>$filter];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }

    public function productDetails_get()
    {
       
        header("Access-Control-Allow-Origin: *");
       
        $data=$this->Fetch_Model->getProducts()->result_array();
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'productdetails', 'data' => $data];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
     public function locations_get()
    {
        header("Access-Control-Allow-Origin: *");
        $data=$this->Fetch_Model->getLocations()->result_array();
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'success', 'data' => $data];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
     public function getCities_get()
    {
        
        header("Access-Control-Allow-Origin: *");
        $district=$this->input->get('district');
        $data=$this->Fetch_Model->getCities($district)->result_array();
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'success', 'data' => $data];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
    public function getArea_get()
    {
        
        header("Access-Control-Allow-Origin: *");
        $city=$this->input->get('city');
        
        $data=$this->Fetch_Model->getArea($city)->result_array();
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'success', 'data' => $data];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
    
    public function getBudget_get()
    {
        
        header("Access-Control-Allow-Origin: *");
        $data=$this->Fetch_Model->getBudget()->result_array();
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'success', 'data' => $data];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
    
     public function categories_get()
    {
        header("Access-Control-Allow-Origin: *");
        $data=$this->Fetch_Model->getCategories()->result_array();
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'success', 'data' => $data];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
    
    
    
}

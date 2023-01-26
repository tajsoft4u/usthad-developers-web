<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("pagination");
    }

    public function index()
    {
        if (!$this->session->userdata('admin_username')) redirect('login');
        $this->load->view('layout/header');
        $this->load->view('layout/home');
        $this->load->view('layout/footer');
    }
    public function login()
    {
        $this->load->view('pages/login');
    }

    // Admin Logins
    public function authLogin() 
    {

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $login_user_details = $this->Master_Model->verifyAdminUsername($username);
        if ($login_user_details->num_rows() > 0) {
            $login_user_details = $login_user_details->row();
            if ($login_user_details->password == $password) {
                $_SESSION['admin_username'] = $login_user_details->username;
                $_SESSION['admin_member_id'] = $login_user_details->id;
                $this->session->set_flashdata('login-success', 'login success...');
                redirect('');
            } else {
                $this->session->set_flashdata('incorrect-password-error', 'incorrect password!!!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('no-account-found-error', 'no account found with this username..');
            redirect('login');
        }
    }

        public function adminLogout() 
    {
        if (!$this->session->userdata('admin_username')) redirect('login');
        $this->session->set_flashdata('logout-success', 'Logout successful');
        $this->session->unset_userdata('admin_username');
        $this->session->unset_userdata('admin_member_id');
        redirect('login');
    }

    public function productList()
    {
       // $data['products']=$this->Fetch_Model->getProducts();
         if (!$this->session->userdata('admin_username')) redirect('login');
        $config = array();
        $config["base_url"] = base_url(). "products";
        $config["total_rows"] = $this->Fetch_Model->getProductsCountWeb();
        $config["uri_segment"] = 2;
        $text=$this->input->get('text');
        $config['full_tag_open'] = "<ul class='pagination pull-right'>";
        $config['full_tag_close'] ="</ul>";
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['anchor_class']= 'class="page-link"';
        $config['prev_tag_open'] = "<li class='page-item'>";
        $config['prev_tag_close'] = "</li>";
        $config['cur_tag_open'] = "<li class='page-item active'><a class='page-link'>"; 
        $config['cur_tag_close'] = '</a></li>'; 
        $config['num_tag_open'] = "<li class='page-item'>";
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = "<li class='page-item'>";
        $config['next_tagl_close'] = "</li>";
        $config['attributes'] = array('class' => 'page-link');
        $config['reuse_query_string'] = true;
        $config["per_page"] = 10;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2))? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['products']=$this->Fetch_Model->productListForFrontByLimit($config["per_page"],$page);
        $data['categories']=$this->Fetch_Model->getCategories();
        $data['cities']=$this->Fetch_Model->getCitiesForWeb();
        $data['locations']=$this->Fetch_Model->getLocations();
        $data['budget']=$this->Fetch_Model->getBudget();
        
       $this->load->view('layout/header');
       $this->load->view('pages/product-list',$data);
       $this->load->view('layout/footer');
   }
   public function locationsList()

   {
     if (!$this->session->userdata('admin_username')) redirect('login');
    $data['locations']=$this->Fetch_Model->getLocations();
    $this->load->view('layout/header');
    $this->load->view('pages/locations',$data);
    $this->load->view('layout/footer');
   }
   public function addProductPage()

   {
     if (!$this->session->userdata('admin_username')) redirect('login');
    $data['categories']=$this->Fetch_Model->getCategories();
    $data['cities']=$this->Fetch_Model->getCitiesForWeb();
    $data['locations']=$this->Fetch_Model->getLocations();
    $data['areas']=$this->Fetch_Model->getAreasForWeb();
     $data['budget']=$this->Fetch_Model->getBudget();
    $this->load->view('layout/header');
    $this->load->view('pages/addProduct',$data);
    $this->load->view('layout/footer');
   }
   public function editProduct()

   {
     if (!$this->session->userdata('admin_username')) redirect('login');
    $data['product']=$this->Fetch_Model->getProductsById($this->uri->segment(2));
    $data['locations']=$this->Fetch_Model->getLocations();
    $data['categories']=$this->Fetch_Model->getCategories();
    $data['cities']=$this->Fetch_Model->getCitiesForWeb();
    $data['areas']=$this->Fetch_Model->getAreasForWeb();
    $data['featureImages']=$this->Fetch_Model->getFeatureImages($this->uri->segment(2));
    $data['budget']=$this->Fetch_Model->getBudget();
    $this->load->view('layout/header');
    $this->load->view('pages/product-page',$data);
    $this->load->view('layout/footer');
   }
   
   public function citiesList()
   {
   
       if (!$this->session->userdata('admin_username')) redirect('login');
      $data['cities']=$this->Fetch_Model->getCitiesForWeb();
      $data['locations']=$this->Fetch_Model->getLocations();
      $this->load->view('layout/header');
      $this->load->view('pages/cities',$data);
      $this->load->view('layout/footer');
   }
   public function categoriesList()
{

    if (!$this->session->userdata('admin_username')) redirect('login');
   $data['categories']=$this->Fetch_Model->getCategories();
   $this->load->view('layout/header');
   $this->load->view('pages/category',$data);
   $this->load->view('layout/footer');
}

   public function areaList()
{
    if (!$this->session->userdata('admin_username')) redirect('login');
   $data['areas']=$this->Fetch_Model->getAreasForWeb();
   $data['cities']=$this->Fetch_Model->getCitiesForWeb();
   $this->load->view('layout/header');
   $this->load->view('pages/area',$data);
   $this->load->view('layout/footer');
}
 public function budgetList()
{

    if (!$this->session->userdata('admin_username')) redirect('login');
   $data['budgets']=$this->Fetch_Model->getBudget();
   $this->load->view('layout/header');
   $this->load->view('pages/budget',$data);
   $this->load->view('layout/footer');
}
 public function addAreaPage()
{
    if (!$this->session->userdata('admin_username')) redirect('login');
   $data['cities']=$this->Fetch_Model->getCitiesForWeb();
   $this->load->view('layout/header');
   $this->load->view('pages/addarea',$data);
   $this->load->view('layout/footer');
}
public function editAreaPage($id)
{
   
    if (!$this->session->userdata('admin_username')) redirect('login');
   $data['cities']=$this->Fetch_Model->getCitiesForWeb();
   $data['area']=$this->Fetch_Model->getAreaById($id);
   $this->load->view('layout/header');
   $this->load->view('pages/editarea',$data);
   $this->load->view('layout/footer');
}
public function getProducts() 
{
    
    $this->Fetch_Model->getProducts();
}
public function productAdd() 
{
    $this->Master_Model->addProducts();
}
public function productEdit() 
{
    $this->Master_Model->editProducts();
}
public function productDelete() 
{
    $this->Master_Model->deleteProducts();
}

public function locationAdd() 
{
    $this->Master_Model->addLocation();
}
public function locationEdit() 
{
    $this->Master_Model->editLocation();
}
public function locationDelete() 
{
    $this->Master_Model->locationDelete();
}
public function categoryAdd() 
{
    $this->Master_Model->addCategory();
}
public function categoryEdit() 
{
    $this->Master_Model->editCategory();
}
public function categoryDelete() 
{
    $this->Master_Model->deleteCategory();
}
public function editFeatureImage() 
{
    $this->Master_Model->editFeatureImage();
}
public function deleteFeaturImages() 
{
    $this->Master_Model->deleteFeaturImages();
}

public function citiesAdd() 
{
    $this->Master_Model->citiesAdd();
}
public function citiesEdit() 
{
    $this->Master_Model->citiesEdit();
}
public function citiesDelete() 
{
    $this->Master_Model->citiesDelete();
}

public function addArea() 
{
    $this->Master_Model->addArea();
}
public function editArea() 
{
    $this->Master_Model->editArea();
}
public function deleteArea() 
{
    $this->Master_Model->deleteArea();
}

public function budgetAdd() 
{
    $this->Master_Model->budgetAdd();
}
public function budgetEdit() 
{
    $this->Master_Model->budgetEdit();
}
public function budgetDelete() 
{
    $this->Master_Model->budgetDelete();
}

}

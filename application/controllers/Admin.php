<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Admin_Model', 'Admin_model', TRUE);
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('string');
        $this->load->library("pagination");
    }

    public function index()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $city_id = $this->session->userdata('id')['city_id'];

        $paramselct = "*";
        $paramtable  = 'tbl_user where status=1 ORDER BY created_date Desc';
        $show['user_count'] = $this->Admin_model->common_join($paramselct, $paramtable);


        $paramselct = "COUNT(*) as totalstore";
        $paramtable  = 'tbl_store where status=1 AND city_id='.$city_id;
        $show['store_count'] = $this->Admin_model->common_join($paramselct, $paramtable);
        $this->load->view('dashboard' ,$show);
    }


    public function login()
    {


        $data         = array('status'=>1);
        $table = 'tbl_city';
        $show['city_list'] = $this->Admin_model->get_data1($data, $table, 'created_date');

        $AllPostData = $this->input->post();
        $this->form_validation->set_rules('email', 'enter email', 'required|trim');
        $this->form_validation->set_rules('password', 'enter password', 'required|min_length[3]');
        $this->form_validation->set_rules('city', 'Select City', 'required');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $email    = $this->input->post('email');
            $password = md5($this->input->post('password'));
            $login_id = $this->Admin_model->Login($email, $password);
            $city_id = $this->input->post('city');
            if ($login_id) {
                foreach ($login_id as $row)
                    $admin_data = array(
                        'id' => $row->id,
                        'email' => $row->user_email,
                        'role_id' => $row->role_id,
                        'password' => $row->password,
                        'city_id' => $city_id, 
                        'status' => '1'
                    );

                $this->session->set_userdata('id', $admin_data);
                return redirect('Admin/index');
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid Username/Password.');
                redirect('Admin/login');
            }
        } 
        $this->load->view('login',$show);
    }


    function store_search()
    {
    }
    //Insert Store
    public function stores()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }
        $city_id = $this->session->userdata('id')['city_id'];

        $show['check_menu_list'] = $this->check_menu_list();

        $config = array();
        $config["base_url"] = base_url() . "Admin/stores";
        $config["total_rows"] = $this->Admin_model->get_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        // $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // $show["links"] = $this->pagination->create_links();
        //$page = ($_GET['pageCount']) ? $_GET['pageCount'] : 0;
        $show['store_list'] = $this->Admin_model->get_stores($config["per_page"], $city_id);


        $search_text = $this->input->post('search_text');
        if ($search_text) {
            $paramSelect1 = " ts.*";
            $paramTable1 = " tbl_store AS ts where ts.store_name LIKE '%" . $search_text . "%'";
            $show['stores'] = $this->Admin_model->common_join($paramSelect1, $paramTable1);
        }



        $created_by = $this->session->userdata('id');

        $paramselct = "COUNT(*) as totalstore";
        $paramtable  = 'tbl_store where status=1 AND city_id='.$city_id;
        $show['store_count'] = $this->Admin_model->common_join($paramselct, $paramtable);

        // $data         = array();
        // $table        = 'tbl_store';
        // $show['store_list'] = $this->Admin_model->get_data($data, $table);


        $data         = array();
        $table        = 'tbl_store_category';
        $show['store_cat'] = $this->Admin_model->get_data($data, $table);


        $storecategory = $this->input->post('store_cat_id');
        //print_r($storecategory);die;

        $this->form_validation->set_rules('store_name', 'Enter Store name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['store_image']['name']) {
                $store_image = $this->Admin_model->uploadEditImage($_FILES['store_image'], "store_image", "neetoAddedimaepath", "./assets/uploads/store");
                $storecat_img = $store_image;
            } else {
                $storecat_img = "";
            }
            $data  = array(
                'created_by' => $created_by['id'],
                'store_name' => $this->input->post('store_name'),
                'description' => $this->input->post('description'),
                'store_image' => $storecat_img,
                'rating' => $this->input->post('rating'),
                'approx_delivery_time' => $this->input->post('approx_delivery_time'),
                'approx_price' => $this->input->post('approx_price'),
                'full_address' => $this->input->post('full_address'),
                'landmark' => $this->input->post('landmark'),
                'pin_code' => $this->input->post('pin_code'),
                'lattitude' => $this->input->post('lattitude'),
                'longitude' => $this->input->post('longitude'),
                'license_code' => $this->input->post('license_code'),
                'store_charge' => $this->input->post('store_charge'),
                'delivery_radius' => $this->input->post('delivery_radius'),
                'is_pure_veg' => $this->input->post('is_pure_veg'),
                'is_featured' => $this->input->post('is_featured'),
                'auto_accept' => $this->input->post('auto_accept'),
                'min_order_price' => $this->input->post('min_order_price'),
                'commission_rate' => $this->input->post('commission_rate'),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            );
            // echo"<pre>";
            // print_r($data);die;
            $table = 'tbl_store';
            $last_id = $this->Admin_model->common_insert($table, $data);
            if ($last_id) {
                for ($i = 0; $i < count($storecategory); $i++) {
                    $stor_cat = array(
                        'store_id' => $last_id,
                        'store_cat_id' => $storecategory[$i],
                    );
                    $table1 = 'tbl_categoryonstore';
                    $this->Admin_model->common_insert($table1, $stor_cat);
                }
            }
            $this->session->set_flashdata('succ_msg', 'Store Added Sucessfully.');
            redirect('Admin/stores');
        } else {
            $this->load->view('stores', $show);
        }
    }
    //Store Update
    public function store_update($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }


        $data         = array();
        $table        = 'tbl_store_category';
        $show['store_cat'] = $this->Admin_model->get_data($data, $table);

        $data         = array('store_id' => $id);
        $table        = 'tbl_store';
        $show['edit_store'] = $this->Admin_model->get_data($data, $table);

        $data         = array('store_id' => $id);
        $table        = 'tbl_categoryonstore';
        $show['edit_storecat'] = $this->Admin_model->get_data($data, $table);

        $dataMonday   = array('store_id' => $id, 'store_day' => 'Monday');
        $dataTuesday   = array('store_id' => $id, 'store_day' => 'Tuesday');
        $dataWednesday   = array('store_id' => $id, 'store_day' => 'Wednesday');
        $dataThursday   = array('store_id' => $id, 'store_day' => 'Thursday');
        $dataFriday   = array('store_id' => $id, 'store_day' => 'Friday');
        $dataSaturday   = array('store_id' => $id, 'store_day' => 'Saturday');
        $dataSunday   = array('store_id' => $id, 'store_day' => 'Sunday');

        $storecategory = $this->input->post('store_cat_id');
        $table_store_time        = 'tbl_store_time';

        $show['mondayStoreTime'] = $this->Admin_model->get_data($dataMonday, $table_store_time);
        $show['tuesdayStoreTime'] = $this->Admin_model->get_data($dataTuesday, $table_store_time);
        $show['wednesdayStoreTime'] = $this->Admin_model->get_data($dataWednesday, $table_store_time);
        $show['thursdayStoreTime'] = $this->Admin_model->get_data($dataThursday, $table_store_time);
        $show['fridayStoreTime'] = $this->Admin_model->get_data($dataFriday, $table_store_time);
        $show['saturdayStoreTime'] = $this->Admin_model->get_data($dataSaturday, $table_store_time);
        $show['sundayStoreTime'] = $this->Admin_model->get_data($dataSunday, $table_store_time);

        $this->form_validation->set_rules('store_name', 'Enter Store name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['store_image']['name']) {
                $store_image = $this->Admin_model->uploadEditImage($_FILES['store_image'], "store_image", "neetoAddedimaepath", "./assets/uploads/store");
                $storecat_img = $store_image;
            } else {
                if ($id) {
                    $storecat_img = $show['edit_store'][0]['store_image'];
                }
            }
            $data  = array(
                'store_name' => $this->input->post('store_name'),
                'description' => $this->input->post('description'),
                'store_image' => $storecat_img,
                'rating' => $this->input->post('rating'),
                'approx_delivery_time' => $this->input->post('approx_delivery_time'),
                'approx_price' => $this->input->post('approx_price'),
                'full_address' => $this->input->post('full_address'),
                'landmark' => $this->input->post('landmark'),
                'pin_code' => $this->input->post('pin_code'),
                'lattitude' => $this->input->post('lattitude'),
                'longitude' => $this->input->post('longitude'),
                'license_code' => $this->input->post('license_code'),
                'store_charge' => $this->input->post('store_charge'),
                'delivery_radius' => $this->input->post('delivery_radius'),
                'is_pure_veg' => $this->input->post('is_pure_veg'),
                'is_featured' => $this->input->post('is_featured'),
                'auto_accept' => $this->input->post('auto_accept'),
                'min_order_price' => $this->input->post('min_order_price'),
                'commission_rate' => $this->input->post('commission_rate'),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            );
            $table = 'tbl_store';
            $condition = array('store_id' => $id);
            $this->Admin_model->common_update($condition, $data, $table);

            $data2        = array('store_id' => $id);
            $table2       = 'tbl_categoryonstore';
            $this->Admin_model->common_delete($data2, $table2);

            if ($id) {
                for ($i = 0; $i < count($storecategory); $i++) {
                    $data1 = array(
                        'store_id' => $id,
                        'store_cat_id' => $storecategory[$i]
                    );
                    $table1 = 'tbl_categoryonstore';
                    $this->Admin_model->common_insert($table1, $data1);
                }
            }

            $monOpenTime = $this->input->post('mon_opening_time');
            $monClosingTime = $this->input->post('mon_closing_time');
            $tueOpenTime = $this->input->post('tue_opening_time');
            $tueClosingTime = $this->input->post('tue_closing_time');
            $wedOpenTime = $this->input->post('wed_opening_time');
            $wedClosingTime = $this->input->post('wed_closing_time');
            $thuOpenTime = $this->input->post('thu_opening_time');
            $thuClosingTime = $this->input->post('thu_closing_time');
            $friOpenTime = $this->input->post('fri_opening_time');
            $friClosingTime = $this->input->post('fri_closing_time');
            $satOpenTime = $this->input->post('sat_opening_time');
            $satClosingTime = $this->input->post('sat_closing_time');
            $sunOpenTime = $this->input->post('sun_opening_time');
            $sunClosingTime = $this->input->post('sun_closing_time');
            // print_r($monOpenTime);die;




            if (empty($show['mondayStoreTime'])) {
                $data1 = array(
                    'store_day' => "Monday",
                    'store_id' => $id,
                    'open_time' => $monOpenTime,
                    'close_time' => $monClosingTime,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table_store_time, $data1);
            } else {
                $data = array(
                    'open_time' => $monOpenTime,
                    'close_time' => $monClosingTime,
                );
                $this->Admin_model->common_update($dataMonday, $data, $table_store_time);
            }
            if (empty($show['tuesdayStoreTime'])) {
                $data1 = array(
                    'store_day' => "Tuesday",
                    'store_id' => $id,
                    'open_time' => $tueOpenTime,
                    'close_time' => $tueClosingTime,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table_store_time, $data1);
            } else {
                $data = array(
                    'open_time' => $tueOpenTime,
                    'close_time' => $tueClosingTime,
                );
                $this->Admin_model->common_update($dataTuesday, $data, $table_store_time);
            }
            if (empty($show['wednesdayStoreTime'])) {
                $data1 = array(
                    'store_day' => "Wednesday",
                    'store_id' => $id,
                    'open_time' => $wedOpenTime,
                    'close_time' => $wedClosingTime,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table_store_time, $data1);
            } else {
                $data = array(
                    'open_time' => $wedOpenTime,
                    'close_time' => $wedClosingTime,
                );
                $this->Admin_model->common_update($dataWednesday, $data, $table_store_time);
            }
            if (empty($show['thursdayStoreTime'])) {
                $data1 = array(
                    'store_day' => "Thursday",
                    'store_id' => $id,
                    'open_time' => $thuOpenTime,
                    'close_time' => $thuClosingTime,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table_store_time, $data1);
            } else {
                $data = array(
                    'open_time' => $thuOpenTime,
                    'close_time' => $thuClosingTime,
                );
                $this->Admin_model->common_update($dataThursday, $data, $table_store_time);
            }
            if (empty($show['fridayStoreTime'])) {
                $data1 = array(
                    'store_day' => "Friday",
                    'store_id' => $id,
                    'open_time' => $friOpenTime,
                    'close_time' => $friClosingTime,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table_store_time, $data1);
            } else {
                $data = array(
                    'open_time' => $friOpenTime,
                    'close_time' => $friClosingTime,
                );
                $this->Admin_model->common_update($dataFriday, $data, $table_store_time);
            }
            if (empty($show['saturdayStoreTime'])) {
                $data1 = array(
                    'store_day' => "Saturday",
                    'store_id' => $id,
                    'open_time' => $satOpenTime,
                    'close_time' => $satClosingTime,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table_store_time, $data1);
            } else {
                $data = array(
                    'open_time' => $satOpenTime,
                    'close_time' => $satClosingTime,
                );
                $this->Admin_model->common_update($dataSaturday, $data, $table_store_time);
            }
            if (empty($show['sundayStoreTime'])) {
                $data1 = array(
                    'store_day' => "Sunday",
                    'store_id' => $id,
                    'open_time' => $sunOpenTime,
                    'close_time' => $sunClosingTime,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table_store_time, $data1);
            } else {
                $data = array(
                    'open_time' => $sunOpenTime,
                    'close_time' => $sunClosingTime,
                );
                $this->Admin_model->common_update($dataSunday, $data, $table_store_time);
            }
            $this->session->set_flashdata('succ_msg', 'Store Updated Sucessfully.');
            redirect('Admin/store_update/' . $id);
        } else {

            $this->load->view('store_update', $show);
        }
    }
    public function off_store()
    {

        $id = $_POST['store_id'];
        if ($id) {
            $save  = array(
                'status' => 0
            );
            $condition = array('store_id' => $id,);
            $table = 'tbl_store';
            $this->Admin_model->common_update($condition, $save, $table);
            echo "Category Off";
            //print_r($save);die;
        }
    }
    public function on_store()
    {

        $id = $_POST['store_id'];
        if ($id) {
            $save  = array(
                'status' => 1
            );
            $condition = array('store_id' => $id,);
            $table = 'tbl_store';
            $this->Admin_model->common_update($condition, $save, $table);
            echo "Category On";
            //print_r($save);die;
        }
    }
    //store category insertion
    public function store_category($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $config = array();
        $config["base_url"] = base_url() . "Admin/store_category/" . $id = "";
        $config["total_rows"] = $this->Admin_model->get_str_cat_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        // $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // $show["links"] = $this->pagination->create_links();
        $show['store_cat_list'] = $this->Admin_model->get_stores_category($config["per_page"], $page);


        $search_text = $this->input->post('search_text');
        if ($search_text) {


            $paramSelect1 = " ts.*";
            $paramTable1 = " tbl_store_category AS ts where ts.store_cat_name LIKE '%" . $search_text . "%'";
            $show['store_cat'] = $this->Admin_model->common_join($paramSelect1, $paramTable1);
        }




        $created_by = $this->session->userdata('id');

        $paramselct = "COUNT(*) as totalcategory";
        $paramtable  = 'tbl_store_category where status=1';
        $show['category_count'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $show['edit_store_cat'] = '';

        // $data         = array();
        // $table        = 'tbl_store_category';
        // $show['store_cat_list'] = $this->Admin_model->get_data($data, $table);

        $this->form_validation->set_rules('store_cat_name', 'Enter Store Category name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['category_imgage']['name']) {
                $category_imgage = $this->Admin_model->uploadEditImage($_FILES['category_imgage'], "category_imgage", "neetoAddedimaepath", "./assets/uploads/storecategory");
                $storecat_img = $category_imgage;
            } else {

                $storecat_img = "";
            }
            $data  = array(
                'created_by' => $created_by['id'],
                'store_cat_name' => $this->input->post('store_cat_name'),
                'description' => $this->input->post('description'),
                'category_imgage' => $storecat_img,
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            );
            $table = 'tbl_store_category';
            $this->Admin_model->common_insert($table, $data);
            $this->session->set_flashdata('succ_msg', 'Category Added Sucessfully.');
            redirect('Admin/store_category');
        } else {
            $this->load->view('store-category', $show);
        }
    }


    public function off_store_cat()
    {

        $id = $_POST['store_cat_id'];
        if ($id) {
            $save  = array(
                'status' => 0
            );
            $condition = array('store_cat_id' => $id,);
            $table = 'tbl_store_category';
            $this->Admin_model->common_update($condition, $save, $table);
            echo "Category Off";
            //print_r($save);die;
        }
    }
    public function on_store_cat()
    {

        $id = $_POST['store_cat_id'];
        if ($id) {
            $save  = array(
                'status' => 1
            );
            $condition = array('store_cat_id' => $id,);
            $table = 'tbl_store_category';
            $this->Admin_model->common_update($condition, $save, $table);
            echo "Category On";
            //print_r($save);die;
        }
    }
    public function update_str_cat($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }


        //$data['marks']  = $this->welcome->get_marks($stu_id);
        if ($id) {
            $data = array(
                'store_cat_id' => $id
            );
            $table = 'tbl_store_category';
            $show['edit_store_cat'] = $this->Admin_model->get_data($data, $table);
        }
        $this->form_validation->set_rules('store_cat_name', 'Enter Product name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['category_imgage']['name']) {
                $category_imgage = $this->Admin_model->uploadEditImage($_FILES['category_imgage'], "category_imgage", "neetoAddedimaepath", "./assets/uploads/storecategory");
                $storecat_img = $category_imgage;
            } else {
                if ($id) {
                    $storecat_img = $show['edit_store_cat'][0]['category_imgage'];
                }
            }

            $data  = array(
                'store_cat_name' => $this->input->post('store_cat_name'),
                'description' => $this->input->post('description'),
                'category_imgage' => $storecat_img
            );
            $table = 'tbl_store_category';
            $condition = array('store_cat_id' => $id);
            $this->Admin_model->common_update($condition, $data, $table);
            $this->session->set_flashdata('succ_msg', 'Category Updated Sucessfully.');
            redirect('Admin/update_str_cat/' . $id);
        } else {

            $this->load->view("update_storeCategory", $show);
        }
    }

    public function item_category($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $created_by = $this->session->userdata('id');
        $store = $this->input->post('store');

        $paramselct = "COUNT(*) as totalitem_category";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $id;
        $show['item_count'] = $this->Admin_model->common_join($paramselct, $paramtable);


        $data         = array();
        $table        = 'tbl_store';
        $show['store_list'] = $this->Admin_model->get_data($data, $table);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $id;
        $show['itemCat_list'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $id . ' AND  parent_category_id > 0';
        $show['itemCat_list_select'] = $this->Admin_model->common_join($paramselct, $paramtable);



        $this->form_validation->set_rules('category_name', 'Enter Product name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['image']['name']) {
                $category_imgage = $this->Admin_model->uploadEditImage($_FILES['image'], "image", "neetoAddedimaepath", "./assets/uploads/itemcategory");
                $itemcat_img = $category_imgage;
            } else {
                $itemcat_img = "";
            }
            $data  = array(
                'created_by' => $created_by['id'],
                'category_name' => $this->input->post('category_name'),
                'description' => $this->input->post('description'),
                'store_id' => $this->input->post('store_id'),
                'image' => $itemcat_img,
                'parent_category_id' => $this->input->post('parent_category_id'),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            );
            $table = 'tbl_item_category';
            $item_catid = $this->Admin_model->common_insert($table, $data);

            $this->session->set_flashdata('succ_msg', 'Category Added Sucessfully.');
            redirect('Admin/item_category/' . $id);
        } else {
            $this->load->view('item-category', $show);
        }
    }
    public function parrent_data($store_id)
    {
        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $store_id . ' AND  parent_category_id = 0';
        $show = $this->Admin_model->common_join($paramselct, $paramtable);

        $output = '<option value="" disabled selected>Parent Category</option><option value="0">None</option>';

        foreach ($show as $row) {
            $output .= '<option value="' . $row["item_cat_id"] . '">' . $row["category_name"] . '</option>';
        }

        echo $output;
    }

    public function item_category_update($item_cat_id)
    {
        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and item_cat_id=' . $item_cat_id;
        $item_category_selected = $this->Admin_model->common_join($paramselct, $paramtable);
        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id = ' . $item_category_selected[0]['store_id'] . ' AND parent_category_id = 0 AND  item_cat_id !=' . $item_cat_id;
        $show = $this->Admin_model->common_join($paramselct, $paramtable);

        $output = '<option value="" disabled selected>Parent Category</option><option value="0">None</option>';

        foreach ($show as $row) {
            if ($row["item_cat_id"] == $item_category_selected[0]['parent_category_id']) {
                $output .= '<option value="' . $row["item_cat_id"] . '" selected>' . $row["category_name"] . '</option>';
            } else {
                $output .= '<option value="' . $row["item_cat_id"] . '">' . $row["category_name"] . '</option>';
            }
        }

        echo $output;
    }
    public function item_update_data($item_id)
    {
        $paramselct = "tic.*,ti.store_id";
        $paramtable  = 'tbl_item AS ti join tbl_item_cat as tic ON tic.item_id = ti.item_id  where status=1 and ti.item_id=' . $item_id;
        $item_category_selected = $this->Admin_model->common_join($paramselct, $paramtable);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $item_category_selected[0]['store_id'] . ' AND  parent_category_id > 0';
        $show = $this->Admin_model->common_join($paramselct, $paramtable);

        $output = '<option value="" disabled>Item Category</option>';

        foreach ($show as $row) {
            for ($i = 0; $i < count($item_category_selected); $i++) {
                if ($row["item_cat_id"] == $item_category_selected[$i]['item_subcat_id']) {
                    $output .= '<option value="' . $row["item_cat_id"] . '" selected>' . $row["category_name"] . '</option>';
                } else {
                    $output .= '<option value="' . $row["item_cat_id"] . '">' . $row["category_name"] . '</option>';
                }
            }
        }

        echo $output;
    }
    public function item_parent_data($store_id)
    {

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $store_id . ' AND  parent_category_id > 0';
        $show['itemCat_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        print_r(json_encode($show));
        die;
    }
    public function parent_data($store_id)
    {
        // echo $output;
        $params_ch = array("store_id" => $store_id);
        $table = 'tbl_item_category';
        $fild = "store_id";
        $view_toc['item_cat_list'] = $this->Admin_model->get_data1($params_ch, $table, $fild);
        // print_r($view_toc);die;
        print_r(json_encode($view_toc));
        die;
        //print_r($save);die;
    }

    public function off_item_cat()
    {

        $id = $_POST['item_cat_id'];
        if ($id) {
            $save  = array(
                'status' => 0
            );
            $condition = array('item_cat_id' => $id,);
            $table = 'tbl_item_category';
            $this->Admin_model->common_update($condition, $save, $table);
            echo "Category Off";
            //print_r($save);die;
        }
    }
    public function on_item_cat()
    {

        $id = $_POST['item_cat_id'];
        if ($id) {
            $save  = array(
                'status' => 1
            );
            $condition = array('item_cat_id' => $id,);
            $table = 'tbl_item_category';
            $this->Admin_model->common_update($condition, $save, $table);
            echo "Category On";
            //print_r($save);die;
        }
    }
    function update_item_category($id)
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }


        $store = $this->input->post('store');

        $data         = array();
        $table        = 'tbl_store';
        $show['store_list'] = $this->Admin_model->get_data($data, $table);

        $data         = array('item_cat_id' => $id);
        $table        = 'tbl_item_category';
        $show['itemCat_edit'] = $this->Admin_model->get_data($data, $table);


        $this->form_validation->set_rules('category_name', 'Enter Category', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['image']['name']) {
                $category_imgage = $this->Admin_model->uploadEditImage($_FILES['image'], "image", "neetoAddedimaepath", "./assets/uploads/itemcategory");
                $itemcat_img = $category_imgage;
            } else {
                if ($id) {
                    $itemcat_img = $show['itemCat_edit'][0]['image'];
                }
            }
            $data  = array(
                'category_name' => $this->input->post('category_name'),
                'description' => $this->input->post('description'),
                'store_id' => $this->input->post('store_id'),
                'image' => $itemcat_img,
                'parent_category_id' => $this->input->post('parent_category_id'),
            );
            $condition = array('item_cat_id' => $id);
            $table = 'tbl_item_category';
            $this->Admin_model->common_update($condition, $data, $table);


            $this->session->set_flashdata('succ_msg', 'Item Updated Sucessfully.');
            redirect('Admin/update_item_category/' . $id);
        } else {
            $this->load->view('update_item_category', $show);
        }
    }



    public function all_items($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $paramselct = "COUNT(*) as totalitem";
        $paramtable  = 'tbl_item where status=1 and store_id=' . $id;
        $show['item_count'] = $this->Admin_model->common_join($paramselct, $paramtable);


        $search_text = $this->input->post('search_text');
        if ($search_text) {


            $paramSelect1 = " ts.*";
            $paramTable1 = " tbl_store AS ts where ts.store_name LIKE '%" . $search_text . "%'";
            $show['stores'] = $this->Admin_model->common_join($paramSelect1, $paramTable1);
        }



        $data         = array('store_id' => $id);
        $table        = 'tbl_store';
        $show['store_list'] = $this->Admin_model->get_data($data, $table);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $id . ' AND  parent_category_id > 0';
        $show['itemCat_list'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $item_cat_id = $this->uri->segment(4);
        $paramselct1 = "ti.*";
        $paramtable1  = 'tbl_item as ti JOIN tbl_item_cat as tic ON ti.item_id=tic.item_id where ti.store_id=' . $id . ' AND tic.item_subcat_id=' . $item_cat_id;
        $show['item_list'] = $this->Admin_model->common_join($paramselct1, $paramtable1);


        $created_by = $this->session->userdata('id');
        $itemCat = $this->input->post('item_cat_id');

        $this->form_validation->set_rules('item_name', 'Enter Product name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            if ($_FILES['item_image']['name']) {
                $item_image = $this->Admin_model->uploadEditImage($_FILES['item_image'], "item_image", "neetoAddedimaepath", "./assets/uploads/item");
                $itemImage = $item_image;
            }
            $data  = array(
                'created_by' => $created_by['id'],
                'item_name' => $this->input->post('item_name'),
                'store_id' => $this->input->post('store_id'),
                'item_type' => $this->input->post('item_type'),
                'description' => $this->input->post('description'),
                'item_image' => $itemImage,
                'price_type' => $this->input->post('price_type'),
                'Is_recommended' => $this->input->post('Is_recommended'),
                'itemQty' => $this->input->post('attribute'),
                'price' => $this->input->post('price'),
                'item_status' => "false",
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            );
            $table = 'tbl_item';
            $item_id = $this->Admin_model->common_insert($table, $data);

            if ($item_id) {
                for ($i = 0; $i < count($itemCat); $i++) {
                    $data1 = array(
                        'item_id' => $item_id,
                        'item_subcat_id' => $itemCat[$i]
                    );
                    $table1 = 'tbl_item_cat';
                    $this->Admin_model->common_insert($table1, $data1);
                }
            }
            $this->session->set_flashdata('succ_msg', 'Item Sucessfully.');
            redirect('Admin/all_items/' . $id . '/' . $item_cat_id);
        } else {
            $this->load->view('all-items', $show);
        }
    }

    public function update_item($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }


        $data         = array('item_id' => $id);
        $table        = 'tbl_item';
        $show['item_edit'] = $this->Admin_model->get_data($data, $table);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $show['item_edit'][0]['store_id'] . ' AND  parent_category_id > 0';
        $show['itemCat_list'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $data         = array();
        $table        = 'tbl_store';
        $show['store_list'] = $this->Admin_model->get_data($data, $table);

        $data         = array('item_id' => $id);
        $table        = 'tbl_item_cat';
        $show['itemCat_edit'] = $this->Admin_model->get_data($data, $table);

        $created_by = $this->session->userdata('id');
        $itemCat = $this->input->post('item_cat_id');

        $this->form_validation->set_rules('item_name', 'Enter Product name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');

            if ($_FILES['item_image']['name']) {
                $item_image = $this->Admin_model->uploadEditImage($_FILES['item_image'], "item_image", "neetoAddedimaepath", "./assets/uploads/item");
                $itemImage = $item_image;
            } else {
                if ($id) {
                    $itemImage = $show['item_edit'][0]['item_image'];
                }
            }
            $data  = array(
                'store_id' => $this->input->post('store_id'),
                'item_name' => $this->input->post('item_name'),
                'item_type' => $this->input->post('item_type'),
                'description' => $this->input->post('description'),
                'item_image' => $itemImage,
                'price_type' => $this->input->post('price_type'),
                'Is_recommended' => $this->input->post('Is_recommended'),
                'itemQty' => $this->input->post('itemQty'),
                'price' => $this->input->post('price'),
            );
            $condition = array('item_id' => $id);
            $table = 'tbl_item';
            $this->Admin_model->common_update($condition, $data, $table);

            $data2        = array('item_id' => $id);
            $table2       = 'tbl_item_cat';
            $this->Admin_model->common_delete($data2, $table2);

            if ($id) {
                for ($i = 0; $i < count($itemCat); $i++) {
                    $data1 = array(
                        'item_id' => $id,
                        'item_subcat_id' => $itemCat[$i]
                    );
                    $table1 = 'tbl_item_cat';
                    $this->Admin_model->common_insert($table1, $data1);
                }
            }
            $this->session->set_flashdata('succ_msg', 'Item Updated Sucessfully.');
            redirect('Admin/update_item/' . $id);
        } else {

            $this->load->view('update_item', $show);
        }
    }

    public function off_item()
    {

        $id = $_POST['item_id'];
        if ($id) {
            $save  = array(
                'status' => 0
            );
            $condition = array('item_id' => $id,);
            $table = 'tbl_item';
            $this->Admin_model->common_update($condition, $save, $table);
            echo "Category Off";
            //print_r($save);die;
        }
    }
    public function on_item()
    {

        $id = $_POST['item_id'];
        if ($id) {
            $save  = array(
                'status' => 1
            );
            $condition = array('item_id' => $id,);
            $table = 'tbl_item';
            $this->Admin_model->common_update($condition, $save, $table);
            echo "Category On";
            //print_r($save);die;
        }
    }

    public function owners_store()
    {

        $id = $_POST['store_id'];
        if ($id) {
            $save  = array(
                'store_id' => $id
            );
            $table = 'tbl_owners_store';
            $this->Admin_model->common_delete($save, $table);
            echo "Store Deleted successfully";
            //print_r($save);die;
        }
    }




    public function all_users()
    {

        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $config = array();
        $config["base_url"] = base_url() . "Admin/all_users/" . $id = "";
        $config["total_rows"] = $this->Admin_model->get_user_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        // $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // $show["links"] = $this->pagination->create_links();
        $show['user_list'] = $this->Admin_model->get_all_user($config["per_page"], $page);
        //         echo"<pre>";
        // print_r($show['user_list']);die;

        $data         = array();
        $table        = 'tbl_roles';
        $show['role_list'] = $this->Admin_model->get_data($data, $table);

        // print_r($show['role_list']);die;
        $paramselct = "tu.*,dg.photo";
        $paramtable  = 'tbl_user AS tu LEFT JOIN delivery_guy_details AS dg ON tu.id=dg.user_id ORDER BY  tu.status DESC, tu.created_date DESC';
        $show['user_list'] = $this->Admin_model->common_join($paramselct, $paramtable);


        $this->form_validation->set_rules('user_name', 'Enter User name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');

            if ($_FILES['photo']['name']) {
                $photo = $this->Admin_model->uploadEditImage($_FILES['photo'], "photo", "neetoAddedimaepath", "./assets/uploads/deliveryGuy");
                $deliveryUyphoto = $photo;
            } else {
                $deliveryUyphoto = "";
            }
            $data  = array(
                'role_id' => $this->input->post('role_id'),
                'user_name' => $this->input->post('user_name'),
                'user_email' => $this->input->post('user_email'),
                'phone_no' => $this->input->post('phone_no'),
                'password' => md5($this->input->post('password')),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            );
            $table = 'tbl_user';
            $user_id = $this->Admin_model->common_insert($table, $data);
            if ($user_id) {
                $data1  = array(
                    'user_id' => $user_id,
                    'name' => $this->input->post('name'),
                    'age' => $this->input->post('age'),
                    'photo' => $deliveryUyphoto,
                    'gender' => $this->input->post('gender'),
                    'description' => $this->input->post('description'),
                    'vehicle_number' => $this->input->post('vehicle_number'),
                    'commission_rate' => $this->input->post('commission_rate'),
                    'created_date' => date('Y-m-d H:i:s'),
                );
                $table1 = 'delivery_guy_details';
                $this->Admin_model->common_insert($table1, $data1);
            }
            $this->session->set_flashdata('succ_msg', 'User Added Successfully.');
            redirect('Admin/all_users');
        } else {


            $this->load->view('all-users', $show);
        }
    }
    public function update_user($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $data         = array();
        $table        = 'tbl_roles';
        $show['role_list'] = $this->Admin_model->get_data($data, $table);

        $data         = array('id' => $id);
        $table        = 'tbl_user';
        $show['user_edit'] = $this->Admin_model->get_data($data, $table);
        $this->form_validation->set_rules('user_name', 'Enter User name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {

            $data  = array(
                'user_name' => $this->input->post('user_name'),
                'user_email' => $this->input->post('user_email'),
                'phone_no' => $this->input->post('phone_no'),
            );
            if ($show['user_edit'][0]['role_id'] != 4) {
                $data['role_id'] = $this->input->post('role');
            }
            if ($this->input->post('password') !== "") {
                $data['password'] = md5($this->input->post('password'));
            }

            // print_r($data);die;
            $condition = array('id' => $id);
            $table = 'tbl_user';
            $this->Admin_model->common_update($condition, $data, $table);


            $this->session->set_flashdata('succ_msg', 'User Updated Successfully.');
            redirect('Admin/update_user/' . $id);
        } else {
            $this->load->view('update_user', $show);
        }
    }
    public function store_owners()
    {

        $show['check_menu_list'] = $this->check_menu_list();

        $config = array();
        $config["base_url"] = base_url() . "Admin/store_owners";
        $config["total_rows"] = $this->Admin_model->get_owner_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        // $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // $show["links"] = $this->pagination->create_links();

        $show['owner_list'] = $this->Admin_model->get_all_owners($config["per_page"], $page);


        // $data         = array('role_id' => 2);
        // $table        = 'tbl_user';
        // $show['owner_list'] = $this->Admin_model->get_data($data, $table);

        $this->load->view('store-owners', $show);
    }
    public function store_owners_store($id)
    {



        $data         = array();
        $table        = 'tbl_owners_store';
        $show['store_list'] = $this->Admin_model->get_data($data, $table);

        $paramselct = "tws.*,ts.store_name";
        $paramtable = "tbl_owners_store as tws join tbl_store as ts on tws.store_id = ts.store_id where tws.owner_id=" . $id;
        $show['assigned_store'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //         echo "<pre>";
        // print_r($show['assigned_store']);die;

        $data         = array();
        $table        = 'tbl_store';
        $show['store_list'] = $this->Admin_model->get_data($data, $table);

        $store_id = $this->input->post('store_id');

        if ($store_id) {
            for ($i = 0; $i < count($store_id); $i++) {
                $data  = array(
                    'owner_id' => $id,
                    'store_id' => $store_id[$i],
                );
                $table = 'tbl_owners_store';
                $this->Admin_model->common_insert($table, $data);
            }
            $this->session->set_flashdata('succ_msg', 'Store Added Sucessfully.');
            redirect('Admin/store_owners_store/' . $id);
        } else {
            $this->load->view('store_owners_store', $show);
        }
    }
    public function update_store_owner($id)
    {


        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }
        $data         = array('id' => $id);
        $table        = 'tbl_user';
        $show['owner_edit'] = $this->Admin_model->get_data($data, $table);

        $this->form_validation->set_rules('user_name', 'Enter User name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $data  = array(
                'user_name' => $this->input->post('user_name'),
                'user_email' => $this->input->post('user_email'),
                'phone_no' => $this->input->post('phone_no'),
                'password' => md5($this->input->post('password')),
                'role_id' => $this->input->post('role'),
            );
            $condition = array('id' => $id);
            $table = 'tbl_user';
            $this->Admin_model->common_update($condition, $data, $table);


            $this->session->set_flashdata('succ_msg', 'User Updated Sucessfully.');
            redirect('Admin/update_user/' . $id);
        } else {


            $this->load->view('update_store_owner', $show);
        }
    }

    public function user_status_ajax()
    {

        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($id) {
            $save  = array(
                'status' => $status
            );
            $condition = array('id' => $id,);
            $table = 'tbl_user';
            $this->Admin_model->common_update($condition, $save, $table);
            if ($status == 1) {
                echo "Active User";
            } else {
                echo "inactive User";
            }
        }
    }

    public function delivery_guys()
    {

        // $show["links"] = $this->pagination->create_links();
        $show['delivery_guy_edit'] = $this->Admin_model->get_delivery_guy('all');
        $show['delivery_guy_status'] = $this->Admin_model->get_delivery_guy();
        $show['check_menu_list'] = $this->check_menu_list();

        // echo '<pre>';
        // print_r($show['delivery_guy_status']);die;
        // $data         = array();
        // $table        = 'delivery_guy_details';
        // $show['delivery_guy_edit'] = $this->Admin_model->get_data($data, $table);

        $this->load->view('delivery_guys', $show);
    }

    public function update_delivery_guye($id)
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $data         = array('delivery_guy_id' => $id);
        $table        = 'delivery_guy_details';
        $show['delivery_guy_edit'] = $this->Admin_model->get_data($data, $table);


        $this->form_validation->set_rules('name', 'Enter User name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');

            if ($_FILES['photo']['name']) {
                $photo = $this->Admin_model->uploadEditImage($_FILES['photo'], "photo", "neetoAddedimaepath", "./assets/uploads/deliveryGuy");
                $deliveryUyphoto = $photo;
            } else {
                if ($id) {
                    $deliveryUyphoto = $show['delivery_guy_edit'][0]['photo'];
                }
            }
            $data  = array(
                'name' => $this->input->post('name'),
                'age' => $this->input->post('age'),
                'gender' => $this->input->post('gender'),
                'photo' => $deliveryUyphoto,
                'vehicle_number' => $this->input->post('vehicle_number'),
                'commission_rate' => $this->input->post('commission_rate'),
                'description' => $this->input->post('role'),
            );
            $condition = array('delivery_guy_id' => $id);
            $table = 'delivery_guy_details';
            $this->Admin_model->common_update($condition, $data, $table);


            $this->session->set_flashdata('succ_msg', 'User Updated Sucessfully.');
            redirect('Admin/update_delivery_guye/' . $id);
        } else {
            $this->load->view('update_delivery_guye', $show);
        }
    }


    public function customers()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }
        $show['check_menu_list'] = $this->check_menu_list();

        $config = array();
        $config["base_url"] = base_url() . "Admin/delivery_guys/" . $id = "";
        $config["total_rows"] = $this->Admin_model->get_customers_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        // $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // $show["links"] = $this->pagination->create_links();
        $show['user_list'] = $this->Admin_model->get_all_customers($config["per_page"], $page);


        // $data         = array('');
        // $table        = 'tbl_user';
        // $show['user_list'] = $this->Admin_model->get_data($data, $table);

        $this->load->view('customers', $show);
    }


    public function admin()
    {

        $this->load->view('admin');
    }


    public function orders()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $city_id = $this->session->userdata('id')['city_id'];

        $show['check_menu_list'] = $this->check_menu_list();

        $date_data = $this->input->post('from_date');
        $str_date = "";
        if ($date_data) {
            $this->session->set_flashdata('from_date', $date_data);
            $dates = explode('-', $date_data);
            $from_date = date('Y-m-d', strtotime($dates[0]));
            $to_date = date('Y-m-d', strtotime($dates[1] . " +1 day"));
            $str_date = ' AND tbo.created_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
        }
        $str = " WHERE order_status = 7 AND city_id=".$city_id ;
        if (isset($_GET['order'])) {
            if ($_GET['order'] == "received") {
                $str = " WHERE city_id=".$city_id ." AND order_status = 1" . $str_date;
            } else if ($_GET['order'] == "accepted") {
                $str = " WHERE city_id=".$city_id ." AND order_status = 2" . $str_date;
            } elseif ($_GET['order'] == "reject") {
                $str = " WHERE city_id=".$city_id ." AND order_status = 3" . $str_date;
            } elseif ($_GET['order'] == "assigned") {
                $str = " WHERE city_id=".$city_id ." AND order_status = 4" . $str_date;
            } elseif ($_GET['order'] == "reached") {
                $str = " WHERE city_id=".$city_id ." AND order_status = 5" . $str_date;
            } elseif ($_GET['order'] == "picked") {
                $str = " WHERE city_id=".$city_id ." AND order_status = 6" . $str_date;
            } elseif ($_GET['order'] == "completed") {
                $str = " WHERE city_id=".$city_id ." AND order_status = 7" . $str_date;
            } elseif ($_GET['order'] == "cancel") {
                $str = " WHERE city_id=".$city_id ." AND order_status = 8" . $str_date;
            } else {
                $str = " WHERE city_id=".$city_id ." AND order_status >= 1" . $str_date;
            }
        }

        $paramAssigned = "tor.*";
        $ordertable = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id where ts.city_id =".$city_id." AND tor.order_status=1";
        $show['placed_order'] = count($this->Admin_model->common_join($paramAssigned, $ordertable));

        $ordertable = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id where ts.city_id =".$city_id." AND tor.order_status=2";
        $show['accepted_order'] = count($this->Admin_model->common_join($paramAssigned, $ordertable));

        $ordertable = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id where ts.city_id =".$city_id." AND tor.order_status=3";
        $show['rejected_order'] = count($this->Admin_model->common_join($paramAssigned, $ordertable));

        $ordertable = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id where ts.city_id =".$city_id." AND tor.order_status=4";
        $show['assigned_order'] = count($this->Admin_model->common_join($paramAssigned, $ordertable));

        $ordertable = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id where ts.city_id =".$city_id." AND tor.order_status=5";
        $show['reached_order'] = count($this->Admin_model->common_join($paramAssigned, $ordertable));

        $ordertable = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id where ts.city_id =".$city_id." AND tor.order_status=6";
        $show['picked_order'] = count($this->Admin_model->common_join($paramAssigned, $ordertable));

        $ordertable = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id where ts.city_id =".$city_id." AND tor.order_status=7";
        $show['delivered_order'] = count($this->Admin_model->common_join($paramAssigned, $ordertable));

        $ordertable = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id where ts.city_id =".$city_id." AND tor.order_status=8";
        $show['cancelled_order'] = count($this->Admin_model->common_join($paramAssigned, $ordertable));

        $paramAssigned1 = "tor.*";
        $paramAssigned2 = "tbl_order as tor JOIN tbl_store as ts ON ts.store_id = tor.store_id  where order_status > 0 AND ts.city_id =".$city_id;
        $show['all_order'] = count($this->Admin_model->common_join($paramAssigned1, $paramAssigned2));


        $paramselct = "tu.user_name,tbo.*,tua.address,ts.store_name";
        $paramtable  = 'tbl_order as tbo join tbl_user as tu on tbo.user_id=tu.id left join tbl_user_address as tua on tbo.address_id=tua.address_id join tbl_store as ts on tbo.store_id=ts.store_id' . $str . " ORDER BY tbo.created_date DESC";
        $show['order_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //     echo"<pre>";
        // print_r($show['order_list']);die;
        $paramselct1 = "*";
        $paramtable1  = 'tbl_user WHERE role_id = 4 AND id NOT IN(Select delivery_staff_id from tbl_order where order_status != 7 AND order_status != 8) AND  id NOT IN(Select delivery_staff_id from tbl_order_pickup_drop where order_status != 4 AND order_status !=5 )';
        $show['staff_list'] = $this->Admin_model->common_join($paramselct1, $paramtable1);

        $this->load->view('orders', $show);
    }
    public function ordersReport()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $city_id = $this->session->userdata('id')['city_id'];

        $date_data = $this->input->post('from_date');
        $str_date = "";
        if ($date_data) {
            $this->session->set_flashdata('from_date', $date_data);
            $dates = explode('-', $date_data);
            $from_date = date('Y-m-d', strtotime($dates[0]));
            $to_date = date('Y-m-d', strtotime($dates[1] . " +1 day"));
            $str_date = ' AND tbo.created_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
        }
        $str = " WHERE order_status = 7 AND city_id=".$city_id. $str_date;
        if (isset($_GET['order'])) {
            if ($_GET['order'] == "cod") {
                $str = " WHERE tbo.order_status = 7  AND city_id=".$city_id." AND tbo.payment_type_id = 1 " . $str_date;
            } else if ($_GET['order'] == "online") {
                $str = " WHERE tbo.order_status = 7 AND tbo.payment_type_id = 2  AND city_id=".$city_id. $str_date;
            }
        }

        $paramselct = "tu.user_name,tbo.*,tua.address,ts.store_name,tt.razorpay_payment_id";
        $paramtable  = 'tbl_order as tbo JOIN tbl_transaction as tt ON tt.order_id = tbo.id join tbl_user as tu on tbo.user_id=tu.id left join tbl_user_address as tua on tbo.address_id=tua.address_id join tbl_store as ts on tbo.store_id=ts.store_id' . $str . " ORDER BY tbo.created_date DESC";
        $show['order_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //     echo"<pre>";
        // print_r($show['order_list']);die;

        $this->load->view('ordersReports', $show);
    }



    public function order_detail($id)
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $paramselct = " tor.id,tor.order_id,tor.sub_total_price,tor.total_price,tor.delivery_fee,tor.	service_charge,tor.offer_discount, tor.order_status,tor.created_date as order_date,tor.updated_date,tor.otp,tor.payment_type_id,tor.payment_status,tor.delivery_fee,ts.store_id,ts.store_name,ts.store_image,tsc.store_cat_id,tsc.store_cat_name,ts.created_date,ts.full_address AS from_address,tua.address_id as to_address_id,tua.address AS to_address,tua.address_type AS to_address_type,tua.name AS to_user_name,tua.mobile_no as to_mobile_no,tc.coupon_id,tc.code AS coupon_code,tor.prescription,tor.reject_reason";
        $paramtable  = 'tbl_order AS tor JOIN tbl_store AS ts ON ts.store_id = tor.store_id LEFT JOIN tbl_categoryonstore AS tcos ON tcos.store_id=ts.store_id LEFT JOIN tbl_store_category AS tsc ON tsc.store_cat_id = tcos.store_cat_id LEFT JOIN tbl_user_address as tua ON tua.address_id = tor.address_id LEFT JOIN tbl_coupon AS tc ON tc.coupon_id = tor.coupon_id where order_status > 0 AND tor.id=' . $id;
        $show['order_detail'] = $this->Admin_model->common_join($paramselct, $paramtable);
        // store owner detaisl
        $paramSelect2 = "tu.*";
        $paramTable2 = "tbl_order AS tor JOIN `tbl_owners_store` as tos ON tor.store_id=tos.store_id JOIN tbl_user AS tu ON tu.id = tos.owner_id WHERE tor.id =" . $id;
        $show['store_owner_details'] = $this->Admin_model->common_join($paramSelect2, $paramTable2);

        // store owner detaisl
        $paramSelect3 = "tu.*";
        $paramTable3 = "tbl_order AS tor JOIN tbl_user AS tu ON tu.id = tor.delivery_staff_id WHERE tor.id =" . $id;
        $show['delivery_guy_details'] = $this->Admin_model->common_join($paramSelect3, $paramTable3);

        $paramselct = "*";
        $paramtable  = 'tbl_order_items where order_id=' . $id;
        $show['item_details'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //     echo"<pre>";
        // print_r($show['item_details']);die;
        $this->load->view('order_detail', $show);
    }

    public function coupon($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $city_id = $this->session->userdata('id')['city_id'];

        $paramselct = "*";
        $paramtable  = 'tbl_store';
        $show['store_list'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $str = "";
        if($id){
            if ($id == 1) {
                $str = " WHERE coupon_type = 1";
            }else if($id == 2){
                $str = " WHERE coupon_type = 2";
            } else {
                $str = " WHERE coupon_type = 0";
            }
        }
        $paramselct = "tc.*,ts.*";
        $paramtable  = 'tbl_coupon as tc left join tbl_store as ts on tc.store_id = ts.store_id' . $str;
        $show['coupon_list'] = $this->Admin_model->common_join($paramselct, $paramtable);


        $this->form_validation->set_rules('name', 'Enter User name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $data  = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'code' => $this->input->post('code'),
                'coupon_type' => $this->input->post('coupontype'),
                'discount_type' => $this->input->post('discount_type'),
                'discount' => $this->input->post('discount'),
                'expiry_date' => $this->input->post('expiry_date'),
                'store_id' => ($this->input->post('store_id')) ? ($this->input->post('store_id')) : (0),
                'count_user' => $this->input->post('count_user'),
                'min_subtotal' => $this->input->post('min_subtotal'),
                'max_discount' => $this->input->post('max_discount'),
                'subtotal_msg' => $this->input->post('subtotal_msg'),
                'to_view_cart' => $this->input->post('to_view_cart'),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            );
            //print_r($data);die;
            $table = 'tbl_coupon';
            $this->Admin_model->common_insert($table, $data);
            $this->session->set_flashdata('succ_msg', 'Coupon Added Sucessfully.');
            redirect('Admin/coupon/');
        } else {
            $this->load->view('coupon', $show);
        }
    }

    public function update_coupon($id)
    {

        $paramselct = "*";
        $paramtable  = 'tbl_coupon where coupon_id=' . $id;
        $show['coupon_edit'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $paramselct = "*";
        $paramtable  = 'tbl_store';
        $show['store_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        $this->form_validation->set_rules('name', 'Enter User name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $data  = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'code' => $this->input->post('code'),
                'coupon_type' => $this->input->post('coupontype'),
                'discount_type' => $this->input->post('discount_type'),
                'discount' => $this->input->post('discount'),
                'expiry_date' => $this->input->post('expiry_date'),
                'store_id' => ($this->input->post('store_id')) ? ($this->input->post('store_id')) : (0),
                'count_user' => $this->input->post('count_user'),
                'min_subtotal' => $this->input->post('min_subtotal'),
                'max_discount' => $this->input->post('max_discount'),
                'subtotal_msg' => $this->input->post('subtotal_msg'),
                'to_view_cart' => $this->input->post('to_view_cart'),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            );
            $condition = array('coupon_id' => $id,);
            $table = 'tbl_coupon';
            $this->Admin_model->common_update($condition, $data, $table);
            $this->session->set_flashdata('succ_msg', 'Coupon Updated Sucessfully.');
            redirect('Admin/update_coupon/' . $id);
        } else {
            $this->load->view('update_coupon', $show);
        }
    }

    function assign_delivery()
    {
        $order_status = 4;
        $order_id = $_POST['order_id'];
        $staff_id = $_POST['staff_id'];

        if ($order_status == 4) {
            $insert_data = array(
                'order_status' => $order_status,
                'delivery_staff_id' => $staff_id
            );
            $getParams = array("order_id" => $order_id, 'user_id' => $staff_id);
            $table = 'tbl_delivery_log';
            $added_data = $this->Admin_model->get_Data($getParams, $table);

            $insert_data1 = array(
                'notification_send' => 0
            );
            if ($added_data) {
                $table_delivery = "tbl_delivery_log";
                $condition = array('order_id' => $order_id, 'user_id' =>  $staff_id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table_delivery);
            } else {
                $insert_data = array(
                    'order_id' => $order_id,
                    'user_id' => $staff_id,
                    'notification_send' => 0
                );
                $table_delivery = "tbl_delivery_log";
                $condition = array('order_id' => $order_id, 'user_id' => $staff_id);
                $output = $this->Admin_model->common_insert($table_delivery, $insert_data);
            }
            $insert_data1 = array(
                'order_status' => 4,
                'delivery_staff_id' => $staff_id
            );
            $table_order = "tbl_order";
            $condition = array('id' => $order_id);
            $output = $this->Admin_model->common_update($condition, $insert_data1, $table_order);


            echo "Assigned successfully";
        }
        die;
    }

    public function delivery_guy_management($id)
    {


        $data12         = array('delivery_guy_id' => $id);
        $table12        = 'delivery_guy_details';
        $show['delivery_guy'] = $this->Admin_model->get_data($data12, $table12);
        $paramselct = "tu.*,dgd.name";
        $paramtable = "tbl_user as tu join delivery_guy_details as dgd on dgd.user_id= tu.id where dgd.delivery_guy_id = " . $id;
        $show['delivery_guy'] = $this->Admin_model->common_join($paramselct, $paramtable);
        if (!empty($show['delivery_guy'])) {
            $paramselct = "tdg.*,ts.store_name";
            $paramtable = "tbl_delivery_guymaagment as tdg join tbl_store as ts on tdg.store_id = ts.store_id where tdg.delivery_guy_id=" . $show['delivery_guy'][0]['id'] . " AND tdg.store_id NOT IN (3)";
            $show['assigned_store'] = $this->Admin_model->common_join($paramselct, $paramtable);
            //         echo "<pre>";
            // print_r($show['assigned_store']);die;

            // $data         = array();
            // $table        = 'tbl_store';
            // $show['store_list'] = $this->Admin_model->get_data($data, $table);

            $paramselct1 = "*";
            $paramtable1 = "tbl_store WHERE store_id NOT IN (SELECT store_id FROM tbl_delivery_guymaagment WHERE delivery_guy_id=" . $show['delivery_guy'][0]['id'] . ")";
            $show['store_list'] = $this->Admin_model->common_join($paramselct1, $paramtable1);
            $store_id = $this->input->post('store_id');
            if ($store_id) {
                for ($i = 0; $i < count($store_id); $i++) {
                    $data  = array(
                        'delivery_guy_id' => $show['delivery_guy'][0]['id'],
                        'store_id' => $store_id[$i],
                    );
                    $table = 'tbl_delivery_guymaagment';
                    $this->Admin_model->common_insert($table, $data);
                }
                $this->session->set_flashdata('succ_msg', 'Store Added Sucessfully.');
                redirect('Admin/delivery_guy_management/' . $id);
            }
        }
        $this->load->view('delivery_guy_management', $show);
    }

    public function delivery_owners_store($id)
    {
        $paramselct = "ts.*";
        $paramtable = "tbl_store as ts join tbl_user as tu on ts.delivery_guy = tu.id where tu.id=" . $id;
        $show['assigned_stores'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //         echo "<pre>";
        // print_r($show['assigned_store']);die;

        $data         = array('status' => 1);
        $table        = 'tbl_store';
        $show['store_list'] = $this->Admin_model->get_data($data, $table);

        $store_id = $this->input->post('store_id');

        if ($store_id) {
            $insert_data1  = array(
                'delivery_guy' => $id,
            );
            $table_order = "tbl_store";
            for ($i = 0; $i < count($store_id); $i++) {
                $condition = array('store_id' => $store_id[$i]);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table_order);
            }
            $this->session->set_flashdata('succ_msg', 'Delivery guy assigned');
            redirect('Admin/delivery_owners_store/' . $id);
        } else {
            $this->load->view('delivery-store-owners', $show);
        }
    }
    public function remove_delivery_store()
    {
        $id = $_POST['store_id'];
        if ($id) {
            $insert_data1  = array(
                'delivery_guy' => 0,
            );
            $table_order = "tbl_store";
            $condition = array('store_id' => $id);
            $output = $this->Admin_model->common_update($condition, $insert_data1, $table_order);
            echo "Delivery guy removed from store";
            //print_r($save);die;
        }
    }
    public function wallet($id)
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }



        $paramSelect = " tu.*";
        $paramTable = " tbl_user AS tu where tu.id=" . $id;
        $show['user_details'] = $this->Admin_model->common_join($paramSelect, $paramTable);

        $this->form_validation->set_rules('amount', 'Enter Amount', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $amount = $this->input->post('amount');
            $description = $this->input->post('description');
            $transaction_type = $this->input->post('transaction_type');

            $wallet_balance = 0;

            $data  = array(
                'user_id' => $id,
                'description' => $description,
                'amount' => $amount,
                'transaction_type' => $transaction_type,
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            );
            $table = 'tbl_wallet';
            $this->Admin_model->common_insert($table, $data);
            if ($transaction_type == "credit") {
                $wallet_balance = $show['user_details'][0]['wallet_balance'] + $amount;
            } else {
                if ($amount >  $show['user_details'][0]['wallet_balance']) {
                    $wallet_balance = 0;
                } else {
                    $wallet_balance = $show['user_details'][0]['wallet_balance'] - $amount;
                }
            }
            $data  = array(
                'wallet_balance' => $wallet_balance,
            );
            $condition = array('id' => $id);
            $table = 'tbl_user';
            $this->Admin_model->common_update($condition, $data, $table);
            redirect('admin/wallet/' . $id);
        }

        // print_r($show['user_details']);die;
        $paramSelect1 = " tw.*";
        $paramTable1 = " tbl_wallet AS tw where tw.user_id=" . $id . " ORDER BY tw.created_date DESC";
        $show['wallet_details'] = $this->Admin_model->common_join($paramSelect1, $paramTable1);


        $this->load->view('wallet', $show);
    }
    function banner($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();


        $data         = array('status' => 1);
        $table        = 'tbl_store_category';
        $show['store_cat'] = $this->Admin_model->get_data($data, $table);


        $paramselct = "tb.*,ts.store_name,tsc.store_cat_name";
        $paramtable  = 'tbl_banners as tb LEFT JOIN tbl_store as ts ON ts.store_id=tb.store_id LEFT JOIN tbl_store_category as tsc ON tsc.store_cat_id=tb.store_cat_id where page_id=' . $id . ' order by tb.type';
        $show['banner_list'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $paramselct = "*";
        $paramtable  = 'tbl_store';
        $show['store_list'] = $this->Admin_model->common_join($paramselct, $paramtable);

        if ($this->input->post('banner_type')) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            if ($_FILES['img_banner']['name']) {
                $img_banner = $this->Admin_model->uploadEditImage($_FILES['img_banner'], "img_banner", "neetoAddedimaepath", "./assets/uploads/banners");
                $imgBanner = $img_banner;
            }


            $data  = array(
                'page_id' => $this->input->post('page'),
                'store_cat_id' => $this->input->post('category'),
                'type' => $this->input->post('banner_type'),
                'store_id' => $this->input->post('store_id'),
                'name' => $imgBanner,
                'status' => 1,
            );
            // print_r($data);die;
            $table = 'tbl_banners';
            $this->Admin_model->common_insert($table, $data);
            $this->session->set_flashdata('succ_msg', 'Banner Added Sucessfully.');
            redirect('Admin/banner/' . $id);
        } else {
            $this->load->view('banner', $show);
        }
    }
    public function banner_delete()
    {

        $id = $_POST['id'];
        if ($id) {
            $save  = array(
                'id' => $id
            );
            $table = 'tbl_banners';
            $this->Admin_model->common_delete($save, $table);
            echo "Banner Deleted successfully";
            //print_r($save);die;
        }
    }
    function online_offline()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }
        $show['check_menu_list'] = $this->check_menu_list();

        $paramselct1 = "*";
        $paramtable1  = 'tbl_user WHERE role_id = 4';
        $show['staff_list'] = $this->Admin_model->common_join($paramselct1, $paramtable1);

        if ($this->input->post('user_id') && $this->input->post('selected_date')) {
            $paramselct1 = "*";
            $paramtable1  = 'tbl_clockin_clockout WHERE user_id = ' . $this->input->post('user_id') . ' AND date="' . $this->input->post('selected_date') . '"';
            $show['online_offline_list'] = $this->Admin_model->common_join($paramselct1, $paramtable1);

            $this->session->set_flashdata('user_id', $this->input->post('user_id'));
            $this->session->set_flashdata('selected_date', $this->input->post('selected_date'));
            // print_r($paramtable1);
            // print_r($show['online_offline_list']);die;
        }

        $this->load->view('clockin_clockout', $show);
    }
    function vehicle($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }
        $show['check_menu_list'] = $this->check_menu_list();

        $data         = array();
        $table = 'tbl_vehicle';
        $show['vehicle_list'] = $this->Admin_model->get_data1($data, $table, 'created_date');
        if ($id) {
            $data = array('vehicle_id' => $id);
            $table = 'tbl_vehicle';
            $show['edit_vehicle'] = $this->Admin_model->get_data($data, $table);
        }
        $this->form_validation->set_rules('name', 'Enter Vehicle Name', 'required|trim');
        $this->form_validation->set_rules('price', 'Enter Price', 'required');
        $this->form_validation->set_rules('kmrange', 'Enter KM range', 'required');
        $this->form_validation->set_rules('priceafter', 'Enter Price after KMS range', 'required');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            if (!$id) {
                $data  = array(
                    'name' => $this->input->post('name'),
                    'price' => $this->input->post('price'),
                    'kmrange' => $this->input->post('kmrange'),
                    'priceafter' => $this->input->post('priceafter'),
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table, $data);
                $this->session->set_flashdata('succ_msg', 'Vehicle Added.');
            } else {
                $insert_data1  = array(
                    'name' => $this->input->post('name'),
                    'price' => $this->input->post('price'),
                    'kmrange' => $this->input->post('kmrange'),
                    'priceafter' => $this->input->post('priceafter'),
                );
                $condition = array('vehicle_id' => $id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
                $this->session->set_flashdata('succ_msg', 'Vehicle Updated.');
            }
            redirect('Admin/vehicle');
        }

        $this->load->view('vehicle', $show);
    }
    public function vehicle_status_ajax()
    {

        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($id) {
            $save  = array(
                'status' => $status
            );
            $condition = array('vehicle_id' => $id,);
            $table = 'tbl_vehicle';
            $this->Admin_model->common_update($condition, $save, $table);
            if ($status == 1) {
                echo "Active Vehicle";
            } else {
                echo "inactive Vehicle";
            }
        }
    }
    function package($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $data         = array();
        $table = 'tbl_package_type';
        $show['package_list'] = $this->Admin_model->get_data1($data, $table, 'created_date');
        if ($id) {
            $data = array('package_id' => $id);
            $show['edit_package'] = $this->Admin_model->get_data($data, $table);
        }
        $this->form_validation->set_rules('name', 'Enter Package Name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            if (!$id) {
                $data  = array(
                    'name' => $this->input->post('name'),
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table, $data);
                $this->session->set_flashdata('succ_msg', 'Package Added.');
            } else {
                $insert_data1  = array(
                    'name' => $this->input->post('name'),
                );
                $condition = array('package_id' => $id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
                $this->session->set_flashdata('succ_msg', 'Package Updated.');
            }
            redirect('Admin/package');
        }

        $this->load->view('package', $show);
    }
    public function package_status_ajax()
    {

        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($id) {
            $save  = array(
                'status' => $status
            );
            $condition = array('package_id' => $id,);
            $table = 'tbl_package_type';
            $this->Admin_model->common_update($condition, $save, $table);
            if ($status == 1) {
                echo "Active Package";
            } else {
                echo "Inactive Package";
            }
        }
    }
    // function distance($id = ""){
    //     if (!$this->session->userdata('id')) {
    //         redirect('Admin/login');
    //     }
    //     $data         = array();
    //     $table = 'tbl_distance';
    //     $show['distance_list'] = $this->Admin_model->get_data1($data, $table,'created_date');
    //     if($id){
    //         $data = array('distance_id'=>$id);
    //         $show['edit_distance'] = $this->Admin_model->get_data($data, $table);
    //     }
    //     $this->form_validation->set_rules('price', 'Enter Distance Price', 'required|trim');
    //     $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
    //     if ($this->form_validation->run()) {
    //         if(!$id){
    //             $data = array('name'=>$this->input->post('name'));
    //             $distanceDetails = $this->Admin_model->get_data($data, $table);
    //             if(empty($distanceDetails)){
    //                 switch($this->input->post('name')){
    //                     case '0-5':
    //                         $nameValue = 5;
    //                         break;
    //                     case '5-10':
    //                         $nameValue = 10;
    //                         break;
    //                     case '10-15':
    //                         $nameValue = 15;
    //                         break;
    //                     case '15-20':
    //                         $nameValue = 20;
    //                         break;
    //                     default:
    //                         $nameValue = 25;
    //                         break;
    //                 }

    //                 $data  = array(
    //                     'name' => $this->input->post('name'),
    //                     'name_value' => $nameValue,
    //                     'price' => $this->input->post('price'),
    //                     'status' => 1,
    //                     'created_date' => date('Y-m-d H:i:s')
    //                 );
    //                 $this->Admin_model->common_insert($table, $data);
    //                 $this->session->set_flashdata('succ_msg', 'Distance price Added.');
    //             }else{
    //                 $this->session->set_flashdata('error_msg', 'Distance price already exists.');
    //             }
    //         }else{
    //             $insert_data1  = array(
    //                 'price' => $this->input->post('price'),
    //             );
    //             $condition = array('distance_id' => $id);
    //             $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
    //             $this->session->set_flashdata('succ_msg', 'Distance Price Updated.');
    //         }
    //         redirect('Admin/distance');
    //     }

    //     $this->load->view('distance',$show);
    // }
    // public function distance_status_ajax()
    // {

    //     $id = $_POST['id'];
    //     $status = $_POST['status'];

    //     if ($id) {
    //         $save  = array(
    //             'status' => $status
    //         );
    //         $condition = array('distance_id' => $id,);
    //         $table = 'tbl_distance';
    //         $this->Admin_model->common_update($condition, $save, $table);
    //         if($status == 1){
    //             echo "Active Distance";
    //         }else{
    //             echo "Inactive Distance";
    //         }
    //     }
    // }
    public function pickup_orders()
    {

        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $str = " WHERE order_status = 4";
        $str1 = "";


        $show['check_menu_list'] = $this->check_menu_list();



        $date_data = $this->input->post('from_date');
        $str_date = "";
        if ($date_data) {
            $this->session->set_flashdata('from_date', $date_data);
            $dates = explode('-', $date_data);
            $from_date = date('Y-m-d', strtotime($dates[0]));
            $to_date = date('Y-m-d', strtotime($dates[1] . " +1 day"));
            $str_date = ' AND tbo.pickup_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
        }
        if (isset($_GET['order'])) {
            if ($_GET['order'] == "assigned") {
                $str1 = ", tu1.user_name as delivery_guy_name,tu1.phone_no as delivery_guy_phone";
                $str = " JOIN tbl_user as tu1 ON tbo.delivery_staff_id = tu1.id WHERE order_status = 2" . $str_date;
            } elseif ($_GET['order'] == "pickedup") {
                $str1 = ", tu1.user_name as delivery_guy_name,tu1.phone_no as delivery_guy_phone";
                $str = " JOIN tbl_user as tu1 ON tbo.delivery_staff_id = tu1.id WHERE order_status = 3" . $str_date;
            } elseif ($_GET['order'] == "completed") {
                $str1 = ", tu1.user_name as delivery_guy_name,tu1.phone_no as delivery_guy_phone";
                $str = " JOIN tbl_user as tu1 ON tbo.delivery_staff_id = tu1.id WHERE order_status = 4" . $str_date;
            } elseif ($_GET['order'] == "cancel") {
                $str = " WHERE order_status = 5" . $str_date;
            } elseif ($_GET['order'] == "reject") {
                $str = " WHERE order_status = 6" . $str_date;
            } elseif ($_GET['order'] == "accepted") {
                $str1 = ", tu1.user_name as delivery_guy_name,tu1.phone_no as delivery_guy_phone";
                $str = " JOIN tbl_user as tu1 ON tbo.delivery_staff_id = tu1.id WHERE order_status = 7" . $str_date;
            } elseif ($_GET['order'] == "received") {
                $str = " WHERE order_status=1 " . $str_date;
            } else {
                $str = " WHERE order_status BETWEEN 1 AND 7" . $str_date;
            }
        }

        $ordertable  = 'tbl_order_pickup_drop';

        $paramAssigned = array('order_status' => 1);
        $show['received_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 2);
        $show['assigned_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 3);
        $show['pickedup_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 4);
        $show['completed_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 5);
        $show['cancelled_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 6);
        $show['rejected_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 7);
        $show['accepted_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array();
        $show['all_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramselct = "tu.user_name,tbo.*,tua1.address as from_address,tua1.name as from_user_name,tua1.mobile_no as from_mobile_no,tua1.latitude as from_lat,tua1.longitude as from_long,tua2.address as to_address,tua2.latitude as to_lat,tua2.longitude as to_long,tua2.name as to_user_name,tua2.mobile_no as to_mobile_no, tv.name as vehicle_name" . $str1;
        $paramtable  = '`tbl_order_pickup_drop` as tbo join tbl_user as tu on tbo.user_id=tu.id LEFT join tbl_user_address as tua1 on tbo.from_address_id=tua1.address_id LEFT JOIN  tbl_user_address as tua2 on tbo.to_address_id=tua2.address_id  LEFT JOIN tbl_vehicle as tv ON tv.vehicle_id = tbo.vehicle_id  ' . $str . " ORDER BY tbo.created_date DESC";
        $show['order_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //     echo"<pre>";
        // print_r($show['order_list']);die;
        $paramselct1 = "*";
        $paramtable1  = 'tbl_user WHERE role_id = 4 AND id NOT IN(Select delivery_staff_id from tbl_order where order_status != 7 AND order_status !=8) AND  id NOT IN(Select delivery_staff_id from tbl_order_pickup_drop where order_status != 4 AND order_status !=5)';
        $show['staff_list'] = $this->Admin_model->common_join($paramselct1, $paramtable1);

        $this->load->view('pickupOrder', $show);
    }
    public function pickupOrderDetail($id)
    {
        // if (!$this->session->userdata('id')) {
        //     redirect('Admin/login');
        // }


        $paramselct = "tu.user_name,tbo.*,tua1.address as from_address,tua1.name as from_user_name,tua1.mobile_no as from_mobile_no,tua1.latitude as from_lat,tua1.longitude as from_long,tua2.address as to_address,tua2.latitude as to_lat,tua2.longitude as to_long,tua2.name as to_user_name,tua2.mobile_no as to_mobile_no, tv.name as vehicle_name,tc.coupon_id,tc.code AS coupon_code";
        $paramtable  = '`tbl_order_pickup_drop` as tbo join tbl_user as tu on tbo.user_id=tu.id LEFT join tbl_user_address as tua1 on tbo.from_address_id=tua1.address_id LEFT JOIN  tbl_user_address as tua2 on tbo.to_address_id=tua2.address_id  LEFT JOIN tbl_vehicle as tv ON tv.vehicle_id = tbo.vehicle_id LEFT JOIN tbl_user as tu1 ON tbo.delivery_staff_id = tu1.id LEFT JOIN tbl_coupon AS tc ON tc.coupon_id = tbo.coupon_id  WHERE tbo.id=' . $id . ' ORDER BY tbo.created_date DESC';
        $show['order_detail'] = $this->Admin_model->common_join($paramselct, $paramtable);



        $this->load->view('pickupOrderDetail', $show);
        // print_r(json_encode($show));die;
    }
    public function ordersPickupReport()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $date_data = $this->input->post('from_date');
        $str_date = "";
        if ($date_data) {
            $this->session->set_flashdata('from_date', $date_data);
            $dates = explode('-', $date_data);
            $from_date = date('Y-m-d', strtotime($dates[0]));
            $to_date = date('Y-m-d', strtotime($dates[1] . " +1 day"));
            $str_date = ' AND tbo.created_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
        }
        $str = " WHERE order_status = 4" . $str_date;
        if (isset($_GET['order'])) {
            if ($_GET['order'] == "cod") {
                $str = " WHERE tbo.order_status = 4 AND tbo.payment_type_id = 1 " . $str_date;
            } else if ($_GET['order'] == "online") {
                $str = " WHERE tbo.order_status = 4 AND tbo.payment_type_id = 2 " . $str_date;
            }
        }


        $paramselct = "tu.user_name,tbo.*,tua1.address as from_address,tua1.name as from_user_name,tua1.mobile_no as from_mobile_no,tua1.latitude as from_lat,tua1.longitude as from_long,tua2.address as to_address,tua2.latitude as to_lat,tua2.longitude as to_long,tua2.name as to_user_name,tua2.mobile_no as to_mobile_no, tv.name as vehicle_name,tt.razorpay_payment_id";
        $paramtable  = '`tbl_order_pickup_drop` as tbo JOIN tbl_transaction_pickup as tt ON tt.order_id = tbo.id JOIN tbl_user as tu on tbo.user_id=tu.id LEFT join tbl_user_address as tua1 on tbo.from_address_id=tua1.address_id LEFT JOIN  tbl_user_address as tua2 on tbo.to_address_id=tua2.address_id  LEFT JOIN tbl_vehicle as tv ON tv.vehicle_id = tbo.vehicle_id  ' . $str . " ORDER BY tbo.created_date DESC";
        $show['order_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //     echo"<pre>";
        // print_r($show['order_list']);die;

        $this->load->view('ordersPickupReports', $show);
    }

    public function pickup_order_detail($id)
    {

        $paramselct = "tu.id,tu.user_name,tu.user_email,tu.phone_no,tbo.*,tua.address,ts.store_name";
        $paramtable  = 'tbl_order as tbo join tbl_user as tu on tbo.user_id=tu.id left join tbl_user_address as tua on tbo.address_id=tua.address_id join tbl_store as ts on tbo.store_id=ts.store_id where tbo.id=' . $id;
        $show['order_detail'] = $this->Admin_model->common_join($paramselct, $paramtable);


        $paramselct = "*";
        $paramtable  = 'tbl_order_items where order_id=' . $id;
        $show['item_details'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //     echo"<pre>";
        // print_r($show['item_details']);die;
        $this->load->view('order_detail', $show);
    }
    function assign_delivery_pickup()
    {
        $order_status = 2;
        $order_id = $_POST['order_id'];
        $staff_id = $_POST['staff_id'];

        if ($order_status == 2) {
            $insert_data = array(
                'order_status' => $order_status,
                'delivery_staff_id' => $staff_id
            );
            $getParams = array("order_id" => $order_id, 'user_id' => $staff_id);
            $table = 'tbl_delivery_log_pickup';
            $added_data = $this->Admin_model->get_Data($getParams, $table);

            $insert_data1 = array(
                'notification_send' => 0
            );
            if ($added_data) {
                $table_delivery = "tbl_delivery_log_pickup";
                $condition = array('order_id' => $order_id, 'user_id' =>  $staff_id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table_delivery);
            } else {
                $insert_data = array(
                    'order_id' => $order_id,
                    'user_id' => $staff_id,
                    'notification_send' => 0
                );
                $table_delivery = "tbl_delivery_log_pickup";
                $condition = array('order_id' => $order_id, 'user_id' => $staff_id);
                $output = $this->Admin_model->common_insert($table_delivery, $insert_data);
            }
            $insert_data1 = array(
                'order_status' => $order_status,
                'delivery_staff_id' => $staff_id
            );
            $table_order = "tbl_order_pickup_drop";
            $condition = array('id' => $order_id);
            $output = $this->Admin_model->common_update($condition, $insert_data1, $table_order);


            echo "Assigned successfully";
        }
        die;
    }
    function targets($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $data         = array();
        $table = 'tbl_delivery_target';
        $show['target_list'] = $this->Admin_model->get_data1($data, $table, 'created_date');
        if ($id) {
            $data = array('id' => $id);
            $show['edit_target'] = $this->Admin_model->get_data($data, $table);
        }
        $this->form_validation->set_rules('target_category', 'Select Category', 'required|trim');
        $this->form_validation->set_rules('title', 'Enter Title', 'required|trim');
        $this->form_validation->set_rules('count', 'Enter count', 'required');
        $this->form_validation->set_rules('from_date', 'Enter Date Range', 'required');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $date_data = $this->input->post('from_date');
            $dates = explode('/', $date_data);
            $start_date = date('Y-m-d', strtotime($dates[0]));
            $end_date = date('Y-m-d', strtotime($dates[1]));
            if (!$id) {
                $data  = array(
                    'target_category' => $this->input->post('target_category'),
                    'title' => $this->input->post('title'),
                    'count' => $this->input->post('count'),
                    'description' => $this->input->post('description'),
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table, $data);
                $this->session->set_flashdata('succ_msg', 'Target Added.');
            } else {
                $insert_data1  = array(
                    'target_category' => $this->input->post('target_category'),
                    'title' => $this->input->post('title'),
                    'count' => $this->input->post('count'),
                    'description' => $this->input->post('description'),
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                );
                $condition = array('id' => $id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
                $this->session->set_flashdata('succ_msg', 'Target Updated.');
            }
            redirect('Admin/targets');
        }

        $this->load->view('targets', $show);
    }
    public function target_status_ajax()
    {

        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($id) {
            $save  = array(
                'status' => $status
            );
            $condition = array('id' => $id,);
            $table = 'tbl_delivery_target';
            $this->Admin_model->common_update($condition, $save, $table);
            if ($status == 1) {
                echo "Active Target";
            } else {
                echo "inactive Target";
            }
        }
    }
    public function targets_assign($id)
    {

        $paramselct = "tu.*,dgd.name";
        $paramtable = "tbl_user as tu join delivery_guy_details as dgd on dgd.user_id= tu.id where tu.role_id=4 AND tu.id NOT IN ( SELECT user_id from tbl_assign_target where target_id=" . $id . ")";
        $show['delivery_guy'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $paramselct = "tat.*,tu.user_name";
        $paramtable = "tbl_assign_target as tat join tbl_user as tu on tat.user_id = tu.id where tu.role_id=4 AND tat.target_id=" . $id;
        $show['assigned_delivery'] = $this->Admin_model->common_join($paramselct, $paramtable);

        if (!empty($show['delivery_guy'])) {

            $delivery_guy_id = $this->input->post('delivery_guy_id');
            if (!empty($delivery_guy_id)) {
                if ($delivery_guy_id[0] == "all") {
                    $delivery_guy = $show['delivery_guy'];
                    // print_r($delivery_guy);die;
                    for ($i = 0; $i < count($delivery_guy); $i++) {
                        $data  = array(
                            'target_id' => $id,
                            'user_id' => $delivery_guy[$i]['id'],
                            'status' => 1,
                            'created_date' => date('Y-m-d H:i:s')
                        );
                        $table = 'tbl_assign_target';
                        $this->Admin_model->common_insert($table, $data);
                    }
                } else {
                    for ($i = 0; $i < count($delivery_guy_id); $i++) {
                        $data  = array(
                            'target_id' => $id,
                            'user_id' => $delivery_guy_id[$i],
                            'status' => 1,
                            'created_date' => date('Y-m-d H:i:s')
                        );
                        $table = 'tbl_assign_target';
                        $this->Admin_model->common_insert($table, $data);
                    }
                }
                $this->session->set_flashdata('succ_msg', 'Delivery assigned.');
                redirect('Admin/targets_assign/' . $id);
            }
        }
        $this->load->view('target_assign', $show);
    }
    public function target_assign_ajax()
    {

        $id = $_POST['target_id'];
        if ($id) {
            $save  = array(
                'id' => $id
            );
            $table = 'tbl_assign_target';
            $this->Admin_model->common_delete($save, $table);
            echo "Delivery Unassigned";
            //print_r($save);die;
        }
    }
    function service($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $data         = array();
        $table = 'tbl_service';
        $show['service_list'] = $this->Admin_model->get_data1($data, $table, 'order_number');
        if ($id) {
            $data = array('service_id' => $id);
            $table = 'tbl_service';
            $show['edit_service'] = $this->Admin_model->get_data($data, $table);
        }
        $this->form_validation->set_rules('name', 'Enter Service Name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {

            $config = ['upload_path'   => './uploads'];
            $this->load->library('upload', $config);
            // print_r($_FILES);die;
            if ($_FILES['image']['name'] != "") {
                $image = $this->Admin_model->uploadEditImage($_FILES['image'], "image", "neetoAddedimaepath", "./assets/uploads/service");
                $img = $image;
            } else {
                if ($id) {
                    $img = $show['edit_service'][0]['image'];
                } else {
                    $img = "";
                }
            }


            if (!$id) {
                $data  = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'order_number' => $this->input->post('order_number'),
                    'image' => $img,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table, $data);
                $this->session->set_flashdata('succ_msg', 'Service Added.');
            } else {
                $insert_data1  = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'order_number' => $this->input->post('order_number'),
                    'image' => $img,
                );
                $condition = array('service_id' => $id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
                $this->session->set_flashdata('succ_msg', 'Service Updated.');
            }
            redirect('Admin/service');
        }

        $this->load->view('service', $show);
    }
    public function service_status_ajax()
    {

        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($id) {
            $save  = array(
                'status' => $status
            );
            $condition = array('service_id' => $id,);
            $table = 'tbl_service';
            $this->Admin_model->common_update($condition, $save, $table);
            if ($status == 1) {
                echo "Active Service";
            } else {
                echo "inactive Service";
            }
        }
    }
    function serviceCategory($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $data         = array('status' => 1);
        $table = 'tbl_service';
        $show['service_list'] = $this->Admin_model->get_data1($data, $table, 'created_date');

        $str = "";
        if (isset($_GET['service']) && $_GET['service'] != "") {
            $strParams = "?service=" . $_GET['service'];
            // print_r($strParams);die;
            $str = " WHERE tsc.service_id=" . $_GET['service'];
        }

        $paramData  = "tsc.*,ts.name as service_name";
        $paramTable = 'tbl_service_cat AS tsc JOIN tbl_service AS ts ON ts.service_id=tsc.service_id ' . $str . ' ORDER BY tsc.order_number DESC';
        $show['service_cat_list'] = $this->Admin_model->common_join($paramData, $paramTable);
        if ($id) {
            $data = array('service_cat_id' => $id);
            $table = 'tbl_service_cat';
            $show['edit_service_cat'] = $this->Admin_model->get_data($data, $table);
        }
        $this->form_validation->set_rules('title', 'Enter Service Name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {

            $config = ['upload_path'   => './uploads'];
            $this->load->library('upload', $config);
            // print_r($_FILES['image']);die;
            if ($_FILES['image']['name'] != "") {
                $image = $this->Admin_model->uploadEditImage($_FILES['image'], "image", "neetoAddedimaepath", "./assets/uploads/service");
                $img = $image;
            } else {
                if ($id) {
                    $img = $show['edit_service_cat'][0]['image'];
                } else {
                    $img = "";
                }
            }

            $table = 'tbl_service_cat';
            if (!$id) {
                $data  = array(
                    'service_id' => $this->input->post('service_id'),
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'order_number' => $this->input->post('order_number'),
                    'image' => $img,
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->Admin_model->common_insert($table, $data);
                $this->session->set_flashdata('succ_msg', 'Service Category Added.');
            } else {
                $insert_data1  = array(
                    'service_id' => $this->input->post('service_id'),
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'order_number' => $this->input->post('order_number'),
                    'image' => $img,
                );
                $condition = array('service_cat_id' => $id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
                $this->session->set_flashdata('succ_msg', 'Service Category Updated.');
            }
            redirect('Admin/serviceCategory' . $strParams);
        }

        $this->load->view('serviceCategory', $show);
    }
    public function service_cat_status_ajax()
    {

        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($id) {
            $save  = array(
                'status' => $status
            );
            $condition = array('service_cat_id' => $id,);
            $table = 'tbl_service_cat';
            $this->Admin_model->common_update($condition, $save, $table);
            if ($status == 1) {
                echo "Active Service Category";
            } else {
                echo "inactive Service Category";
            }
        }
    }
    function servicePlan($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $data         = array('status' => 1);
        $table = 'tbl_service_cat';
        $show['service_cat_list'] = $this->Admin_model->get_data1($data, $table, 'created_date');

        $str = "";
        if (isset($_GET['serviceCat']) && $_GET['serviceCat'] != "") {
            $strParams = "?serviceCat=" . $_GET['serviceCat'];
            // print_r($strParams);die;
            $str = " WHERE tsp.service_cat_id=" . $_GET['serviceCat'];
        }

        $paramData  = "tsp.*,tsc.title as service_cat_title";
        $paramTable = 'tbl_service_cat AS tsc JOIN tbl_service_plan AS tsp ON tsp.service_cat_id=tsc.service_cat_id ' . $str . ' ORDER BY tsp.created_date DESC';
        $show['service_plan_list'] = $this->Admin_model->common_join($paramData, $paramTable);
        if ($id) {
            $data = array('plan_id' => $id);
            $table = 'tbl_service_plan';
            $show['edit_service_plan'] = $this->Admin_model->get_data($data, $table);

            $data = array('plan_id' => $id);
            $table = 'tbl_plan_question';
            $show['edit_plan_questions'] = $this->Admin_model->get_data($data, $table);
        }
        $this->form_validation->set_rules('title', 'Enter Plan Name', 'required|trim');
        $this->form_validation->set_rules('amount', 'Enter Plan Amount', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {

            $config = ['upload_path'   => './uploads'];
            $this->load->library('upload', $config);
            // print_r($_FILES['image']);die;
            if ($_FILES['image']['name'] != "") {
                $image = $this->Admin_model->uploadEditImage($_FILES['image'], "image", "neetoAddedimaepath", "./assets/uploads/service");
                $img = $image;
            } else {
                if ($id) {
                    $img = $show['edit_service_plan'][0]['image'];
                } else {
                    $img = "";
                }
            }





            $table = 'tbl_service_plan';
            if (!$id) {
                $data  = array(
                    'service_cat_id' => $this->input->post('service_cat_id'),
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'image' => $img,
                    'amount' => $this->input->post('amount'),
                    'is_recommended' => $this->input->post('is_recommended'),
                    'status' => 1,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $plan_id = $this->Admin_model->common_insert($table, $data);
                $this->session->set_flashdata('succ_msg', 'Service Category Added.');
            } else {
                $insert_data1  = array(
                    'service_cat_id' => $this->input->post('service_cat_id'),
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'amount' => $this->input->post('amount'),
                    'is_recommended' => $this->input->post('is_recommended'),
                    'image' => $img,
                );
                $condition = array('plan_id' => $id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
                $plan_id = $id;
                $this->session->set_flashdata('succ_msg', 'Plan Updated.');
            }


            $question = $this->input->post('question');
            $question_type = $this->input->post('question_type');
            $options = $this->input->post('options');
            $question_id = $this->input->post('question_id');
            $is_mandatory = $this->input->post('is_mandatory');
            // print_r(empty($question));die;
            if (!empty($question)) {
                for ($j = 0; $j < count($question); $j++) {
                    if ($question[$j] !=  "") {
                        $data4 = array(
                            'plan_id' => $plan_id,
                            'question' => $question[$j],
                            'type' => $question_type[$j],
                            'options' => $options[$j],
                            'is_mandatory' => $is_mandatory[$j],
                            'status' => 1,
                            'created_date' => date('y-m-d H:i:s')
                        );
                        //print_r($data4);die;
                        $table4 = "tbl_plan_question";
                        if (!isset($question_id[$j])) {
                            $last_option_id = $this->Admin_model->common_insert($table4, $data4);
                        } else {
                            $condition = array('id' => $question_id[$j]);
                            $this->Admin_model->common_update($condition, $data4, $table4);
                        }
                    }
                }
            }



            redirect('Admin/servicePlan' . $strParams);
        }

        $this->load->view('servicePlan', $show);
    }
    public function service_plan_status_ajax()
    {

        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($id) {
            $save  = array(
                'status' => $status
            );
            $condition = array('plan_id' => $id,);
            $table = 'tbl_service_plan';
            $this->Admin_model->common_update($condition, $save, $table);
            if ($status == 1) {
                echo "Active Plan";
            } else {
                echo "inactive Plan";
            }
        }
    }
    public function delete_plan_question()
    {
        if (!$this->session->userdata('id')) {
            redirect(base_url('Admin/logout'));
        }
        $id = $_POST['id'];
        if ($id) {
            $save = array(
                'id' => $id,
            );
            $table = 'tbl_plan_question';
            $this->Admin_model->common_delete($save, $table);
            echo "Option Deleted Successfully";
        }
    }
    public function service_vendor_assign($id)
    {

        $paramselct = "tu.*";
        $paramtable = "tbl_user as tu where tu.role_id=2 AND tu.id NOT IN ( SELECT user_id from tbl_service_vendor where service_cat_id=" . $id . ")";
        $show['vendors'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $paramselct = "tsv.*,tu.user_name";
        $paramtable = "tbl_service_vendor as tsv join tbl_user as tu on tsv.user_id = tu.id where tu.role_id=2 AND tsv.service_cat_id=" . $id;
        $show['assigned_vendor'] = $this->Admin_model->common_join($paramselct, $paramtable);

        if (!empty($show['vendors'])) {

            $vendor_id = $this->input->post('vendor_id');
            if (!empty($vendor_id)) {
                if ($vendor_id[0] == "all") {
                    $vendors = $show['vendors'];
                    // print_r($delivery_guy);die;
                    for ($i = 0; $i < count($vendors); $i++) {
                        $data  = array(
                            'service_cat_id' => $id,
                            'user_id' => $vendors[$i]['id'],
                            'status' => 1,
                            'created_date' => date('Y-m-d H:i:s')
                        );
                        $table = 'tbl_service_vendor';
                        $this->Admin_model->common_insert($table, $data);
                    }
                } else {
                    for ($i = 0; $i < count($vendor_id); $i++) {
                        $data  = array(
                            'service_cat_id' => $id,
                            'user_id' => $vendor_id[$i],
                            'status' => 1,
                            'created_date' => date('Y-m-d H:i:s')
                        );
                        $table = 'tbl_service_vendor';
                        $this->Admin_model->common_insert($table, $data);
                    }
                }
                $this->session->set_flashdata('succ_msg', 'Vendor assigned.');
                redirect('Admin/service_vendor_assign/' . $id);
            }
        }
        $this->load->view('vendor_service', $show);
    }
    public function vendor_service_assign_ajax()
    {

        $id = $_POST['id'];
        if ($id) {
            $save  = array(
                'id' => $id
            );
            $table = 'tbl_service_vendor';
            $this->Admin_model->common_delete($save, $table);
            echo "Vendor Unassigned";
            //print_r($save);die;
        }
    }
    public function serviceOrders()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $date_data = $this->input->post('from_date');
        $str_date = "";
        if ($date_data) {
            $this->session->set_flashdata('from_date', $date_data);
            $dates = explode('-', $date_data);
            $from_date = date('Y-m-d', strtotime($dates[0]));
            $to_date = date('Y-m-d', strtotime($dates[1] . " +1 day"));
            $str_date = ' AND tbo.created_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
        }
        $str = " WHERE order_status = 4";
        if (isset($_GET['order'])) {
            if ($_GET['order'] == "received") {
                $str = " WHERE order_status = 1" . $str_date;
            } else if ($_GET['order'] == "accepted") {
                $str = " WHERE order_status = 2" . $str_date;
            } elseif ($_GET['order'] == "reject") {
                $str = " WHERE order_status = 3" . $str_date;
            } elseif ($_GET['order'] == "completed") {
                $str = " WHERE order_status = 4" . $str_date;
            } elseif ($_GET['order'] == "cancelled") {
                $str = " WHERE order_status = 5" . $str_date;
            } else {
                $str = " WHERE order_status >= 1" . $str_date;
            }
        }
        $ordertable = "tbl_order_service";
        $paramAssigned = array('order_status' => 1);
        $show['received_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 2);
        $show['accepted_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 3);
        $show['rejected_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 4);
        $show['completed_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned = array('order_status' => 5);
        $show['cancelled_order'] = count($this->Admin_model->get_data($paramAssigned, $ordertable));

        $paramAssigned1 = "*";
        $paramAssigned2 = "tbl_order_service where order_status > 0";
        $show['all_order'] = count($this->Admin_model->common_join($paramAssigned1, $paramAssigned2));


        $paramselct = "tu.user_name,tbo.*,tua.address";
        $paramtable  = 'tbl_order_service as tbo join tbl_user as tu on tbo.user_id=tu.id left join tbl_user_address as tua on tbo.address_id=tua.address_id ' . $str . " ORDER BY tbo.created_date DESC";
        $show['order_list'] = $this->Admin_model->common_join($paramselct, $paramtable);


        //     echo"<pre>";
        // print_r($show['order_list']);die;
        // $paramselct1 = "*";
        // $paramtable1  = 'tbl_user WHERE role_id = 4 AND id NOT IN(Select delivery_staff_id from tbl_order where order_status != 7 AND order_status != 8) AND  id NOT IN(Select delivery_staff_id from tbl_order_pickup_drop where order_status != 4 AND order_status !=5 )';
        // $show['staff_list'] = $this->Admin_model->common_join($paramselct1, $paramtable1);

        $this->load->view('serviceOrder', $show);
    }

    public function serviceOrderDetail($id)
    {
        // if (!$this->session->userdata('id')) {
        //     redirect('Admin/login');
        // }

        $paramselct = "tor.*,tua.name as to_user_name,tua.mobile_no as to_mobile_no,tua.address as to_address,tc.coupon_id,tc.code AS coupon_code";
        $paramtable  = 'tbl_order_service as tor JOIN tbl_user_address as tua ON tua.address_id=tua.address_id LEFT JOIN tbl_coupon AS tc ON tc.coupon_id = tor.coupon_id where tor.id=' . $id;
        $show['order_detail'] = $this->Admin_model->common_join($paramselct, $paramtable);

        $paramselct = "toi.*";
        $paramtable  = 'tbl_order_item_service as toi where toi.order_id=' . $id;
        $show['item_details'] = $this->Admin_model->common_join($paramselct, $paramtable);
        // print_r($show['item_details']);die;
        // store owner detaisl
        $paramSelect2 = "tu.*,tor.*";
        $paramTable2 = "tbl_order_service AS tor JOIN tbl_user as tu ON tu.id=tor.vendor_id WHERE tu.role_id = 2 AND tor.id =" . $id;
        $show['vendor_details'] = $this->Admin_model->common_join($paramSelect2, $paramTable2);

        $paramselct3 = "tosa.*";
        $paramtable3  = 'tbl_order_service_answers as tosa where tosa.order_id=' . $id;
        $show['answers_details'] = $this->Admin_model->common_join($paramselct3, $paramtable3);

        $this->load->view('serviceOrderDetail', $show);
        // print_r(json_encode($show));die;
    }
    public function ordersServiceReport()
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }

        $show['check_menu_list'] = $this->check_menu_list();

        $date_data = $this->input->post('from_date');
        $str_date = "";
        if ($date_data) {
            $this->session->set_flashdata('from_date', $date_data);
            $dates = explode('-', $date_data);
            $from_date = date('Y-m-d', strtotime($dates[0]));
            $to_date = date('Y-m-d', strtotime($dates[1] . " +1 day"));
            $str_date = ' AND tbo.created_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
        }
        $str = " WHERE tbo.order_status = 4" . $str_date;
        if (isset($_GET['order'])) {
            if ($_GET['order'] == "cod") {
                $str = " WHERE tbo.order_status = 4 AND tbo.payment_type_id = 1 " . $str_date;
            } else if ($_GET['order'] == "online") {
                $str = " WHERE tbo.order_status = 4 AND tbo.payment_type_id = 2 " . $str_date;
            }
        }


        $paramselct = "tu.user_name,tbo.*,tua.address,tt.razorpay_payment_id";
        $paramtable  = 'tbl_order_service as tbo JOIN tbl_transaction_service as tt ON tt.order_id = tbo.id join tbl_user as tu on tbo.user_id=tu.id left join tbl_user_address as tua on tbo.address_id=tua.address_id ' . $str . ' ORDER BY tbo.created_date DESC ';
        $show['order_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        //     echo"<pre>";
        // print_r($show['order_list']);die;

        $this->load->view('ordersServiceReports', $show);
    }




    public function role($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        } else {
            $session_arr = $this->session->userdata('id');
        }
        $this->form_validation->set_rules('user_role', 'Enter role', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $user_role = $this->input->post('user_role');
        $params_ch = array('status' => 1);
        $table = 'tbl_roles';
        $fild = "role";
        $data['user_role_list'] = $this->Admin_model->get_data($params_ch, $table, $fild);

        //function call for validate side menu list 
        $data['check_menu_list'] = $this->check_menu_list();

        $params_ch = array();
        $table = 'tbl_user_access';
        $fild = "id";
        $data['user_access_list'] = $this->Admin_model->get_data($params_ch, $table, $fild);
        //print_r($data['user_access_list']);die;

        $params_ch = array('status' => 1);
        $table = 'tbl_menu';
        $fild = "id";
        $data['menu_list'] = $this->Admin_model->get_data($params_ch, $table, $fild);
        //print_r($this->session->userdata('admin_id'));die;
        $ids = "";
        $data['edit_user_role'] = "";
        if ($id) {
            $con_id = array('id' => $id);
            $fild = "id";
            $table1 = 'tbl_roles';
            $data['edit_user_role'] = $this->Admin_model->get_data($con_id, $table1, $fild);
        }
        if ($this->form_validation->run()) {
            $user_role = $this->input->post('user_role');
            $params_ch = array('role' => $user_role, 'status' => 1);
            $table = 'tbl_roles';
            $fild = "id";
            $validate_name_active = $this->Admin_model->get_data($params_ch, $table, $fild);

            $user_role = $this->input->post('user_role');
            $params_ch = array('role' => $user_role, 'status' => 0);
            $table = 'tbl_roles';
            $fild = "id";
            $validate_name_deleted = $this->Admin_model->get_data($params_ch, $table, $fild);
            if ($validate_name_deleted || $id) {
                $save = array(
                    'role'        => $user_role,
                    'description' => "",
                    'status'      => 1,
                    'updated_by'  => $session_arr['admin_id'],
                );
                $table = 'tbl_roles';
                if ($id) {
                    $condation =  array('id' => $id);
                    $this->Admin_model->common_update($condation, $save, $table);
                    $this->session->set_flashdata('succ_msg', 'Role Updated');
                    $ids = "/" . $id;
                } else {
                    $condation =  $params_ch;
                    $this->Admin_model->common_update($condation, $save, $table);
                    $this->session->set_flashdata('succ_msg', 'Role Added');
                }
            } else {
                //print_r($validate_name_active);die;
                if (!$validate_name_active) {
                    $save = array(
                        'role'        => $user_role,
                        'description' => "",
                        'status'      => 1,
                        'created_by'  => $session_arr['id'],
                        'updated_by'  => $session_arr['id'],
                        'created_on'  => date('Y-m-d H:i:s'),
                    );
                    $table = 'tbl_roles';
                    $this->Admin_model->common_insert($table, $save);
                    $this->session->set_flashdata('succ_msg', 'Role Added');
                } else {
                    $this->session->set_flashdata('succ_erro', 'Role Already Exists');
                }
            }
            redirect('admin/role' . $ids);
        }
        $this->load->view('role' . $ids, $data);
    }

    public function role_access_update()
    {
        $session_arr = $this->session->userdata('id');
        $menu_id    =   $_POST['menu_id'];
        $menu_status =   $_POST['menu_status'];
        $role_user_id =   $_POST['role_user_id'];
        $val_status =   $_POST['val_status'];
        // $data['notification'] = $this->get_notification();
        $params_ch = array('menu_id' => $menu_id, 'user_id' => $role_user_id);
        $table = 'tbl_user_access';
        $fild = "id";
        $validate_access = $this->Admin_model->get_data($params_ch, $table, $fild);
        if ($validate_access) {
            $save = array(
                $menu_status  => $val_status,
                'updated_by'  => $session_arr['id'],
            );
            //print_r($save);die;
            $table = 'tbl_user_access';
            $condation =  array('menu_id' => $menu_id, 'user_id' => $role_user_id);
            $this->Admin_model->common_update($condation, $save, $table);
        } else {

            $save = array(
                'menu_id'     => $menu_id,
                'user_id'     => $role_user_id,
                $menu_status  => $val_status,
                'created_by'  => $session_arr['id'],
                'updated_by'  => $session_arr['id'],
                'created_date'  => date('Y-m-d H:i:s'),
            );
            //print_r($save);die;
            $table = 'tbl_user_access';
            $this->Admin_model->common_insert($table, $save);
        }
    }

    /* Role delete cotroller method for delete Role */
    public function role_delete()
    {
        $role_id = $_POST['role_id'];
        if ($role_id) {
            $save = array(
                'status' => 0,
            );
            $table = 'tbl_roles';
            $condation =  array('id' => $role_id);
            $this->Admin_model->common_update($condation, $save, $table);
            echo 'Deleted Successfully';
        }
        die;
    }


    public function check_menu_list()
    {
        if (!$this->session->userdata('id')) {
            redirect('/');
        } else {
            $session_arr = $this->session->userdata('id');
        }
        // print_r($session_arr);die;
        $params_ch = array('user_id' => $session_arr['role_id']);
        $table = 'tbl_user_access';
        $fild = 'id';
        $check_menu_list = $this->Admin_model->get_data($params_ch, $table, $fild);
        return $check_menu_list;
    }

    public function ordersNotication()
    {
        if (!$this->session->userdata('id')) {
            redirect('/');
        }

        $data['check_menu_list'] = $this->check_menu_list();

        $paramselct = "tu.*,tn.msg,tn.order_id";
        $paramtable  = 'tbl_notification as tn JOIN tbl_user as tu ON tn.user_id = tu.id where type="normal_order" Order BY tn.created_date';
        $data['notification_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        $this->load->view('ordersNotication', $data);
    }
    public function ordersPickupNotication()
    {
        if (!$this->session->userdata('id')) {
            redirect('/');
        }

        $data['check_menu_list'] = $this->check_menu_list();

        $paramselct = "tu.*,tn.msg,tn.order_id";
        $paramtable  = 'tbl_notification as tn JOIN tbl_user as tu ON tn.user_id = tu.id where type="pickup_order" Order BY tn.created_date';
        $data['notification_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        $this->load->view('ordersPickupNotication', $data);
    }
    public function ordersServiceNotication()
    {
        if (!$this->session->userdata('id')) {
            redirect('/');
        }

        $data['check_menu_list'] = $this->check_menu_list();

        $paramselct = "tu.*,tn.msg,tn.order_id";
        $paramtable  = 'tbl_notification as tn JOIN tbl_user as tu ON tn.user_id = tu.id where type="service_order" Order BY tn.created_date';
        $data['notification_list'] = $this->Admin_model->common_join($paramselct, $paramtable);
        $this->load->view('ordersServiceNotication', $data);
    }

    function city($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }
        $show['check_menu_list'] = $this->check_menu_list();

        $data         = array();
        $table = 'tbl_city';
        $show['city_list'] = $this->Admin_model->get_data1($data, $table, 'created_date');
        if ($id) {
            $data = array('id' => $id);
            $table = 'tbl_city';
            $show['edit_city'] = $this->Admin_model->get_data($data, $table);
        }
        $this->form_validation->set_rules('name', 'Enter City Name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $params_ch = array('city_name' => $this->input->post('name'), 'status' => 1);
            $table = 'tbl_city';
            $fild = 'id';
            $validate_name_active = $this->Admin_model->get_data($params_ch, $table, $fild);
// print_r($validate_name_active);die;
            $params_ch = array('city_name' => $this->input->post('name'), 'status' => 0);
            $table = 'tbl_city';
            $fild = 'id';
            $validate_name_deleted = $this->Admin_model->get_data($params_ch, $table, $fild);
            if (!$id) {
                // print_r($validate_name_active);
                // print_r($validate_name_deleted);die;
                if(!empty($validate_name_deleted)){
                    
                    $insert_data1  = array(
                        'status' => 1,
                    );
                    $condition = array('id' => $validate_name_deleted[0]['id']);
                    $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
                    $this->session->set_flashdata('succ_msg', 'City Added.');
                }else if (empty($validate_name_active)) {
                    $data  = array(
                        'city_name' => $this->input->post('name'),
                        'status' => 1,
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    $this->Admin_model->common_insert($table, $data);
                    $this->session->set_flashdata('succ_msg', 'City Added.');
                } else {
                    $this->session->set_flashdata('error_msg', 'City already exists');
                }
            } else {                
                $insert_data1  = array(
                    'city_name' => $this->input->post('name'),
                );
                $condition = array('id' => $id);
                $output = $this->Admin_model->common_update($condition, $insert_data1, $table);
                $this->session->set_flashdata('succ_msg', 'City Updated.');
            }
            redirect('Admin/city');
        }

        $this->load->view('city', $show);
    }
    public function city_status_ajax()
    {

        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($id) {
            $save  = array(
                'status' => $status
            );
            $condition = array('id' => $id,);
            $table = 'tbl_city';
            $this->Admin_model->common_update($condition, $save, $table);
            if ($status == 1) {
                echo "Active City";
            } else {
                echo "inactive City";
            }
        }
    }



    function logout()
    {
        $this->session->unset_userdata('id');
        redirect('Admin/login');
    }
}

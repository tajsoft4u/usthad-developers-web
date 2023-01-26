<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Master_Model extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   public function addProducts($value = '')
   {
      $data = array(
         'title' => $this->input->post('title'),
         'description' =>     $this->input->post('description'),
         'location' => $this->input->post('location'),
         'pcity' => $this->input->post('pcity'),
         'parea' => $this->input->post('parea'),
         'category' => $this->input->post('category'),
         'pbudget' => $this->input->post('budget'),
         'imageUrl' => $this->input->post('imageUrl'),

      );
      $result = $this->db->insert('products', $data);
      $id = $this->db->insert_id();
      $filename = '';
      if ($id != '') {
         if (!empty($_FILES['image']['name'])) {
            $path = $_FILES['image']['name'];
            $newName = $id . "." . pathinfo($path, PATHINFO_EXTENSION);
            $config['overwrite'] = TRUE;
            $config['upload_path'] = './uploads/productImage';
            $config['allowed_types'] = '*';
            $config['file_name'] = $newName;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('image')) {
               $error = array(
                  'error' => $this->upload->display_errors()
               );
               $this->session->set_flashdata('error', 'Something went wrong. Please upload gif | jpg | png file');
            } else {
               $data = $this->upload->data();
               $filename = HOME_PATH . '/uploads/productImage/' . $data['file_name'];
            }
            $updateImage = array(
               'imageUrl' => $filename
            );
            $insertImage = $this->db->set($updateImage)->where('prodId', $id)->update('products');
         }

         $this->multipleImageUpload($id);
      }

      redirect('products');
   }

   public function verifyAdminUsername($id)
   {

      $result = $this->db->where('username', $id)->get('auth');
      return $result;
   }
   public function editProducts()
   {
      $id = $this->input->post('prodId');
      $filename = '';
      if (!empty($_FILES['image']['name'])) {
         $path = $_FILES['image']['name'];
         $result = $this->db->where('prodId', $this->input->post('prodId'))->get('products')->row();
         $oldImagePath = $result->imageUrl;
         $imagePath = str_replace(HOME_PATH, "./", $oldImagePath);
         if ($imagePath) {
            @unlink($imagePath);
         }
         $newName = $id . "." . pathinfo($path, PATHINFO_EXTENSION);
         $config['overwrite'] = TRUE;
         $config['upload_path'] = './uploads/productImage';
         $config['allowed_types'] = '*';
         $config['file_name'] = $newName;
         $this->load->library('upload', $config);
         $this->upload->initialize($config);
         if (!$this->upload->do_upload('image')) {
            $error = array(
               'error' => $this->upload->display_errors()
            );
            $this->session->set_flashdata('error', 'Something went wrong. Please upload gif | jpg | png file');
         } else {
            $data = $this->upload->data();
            $filename = HOME_PATH . 'uploads/productImage/' . $data['file_name'];
         }
         $updateImage = array(
            'imageUrl' => $filename
         );
         $insertImage = $this->db->set($updateImage)->where('prodId', $id)->update('products');
      } else {
         $filename = $this->input->post('old_photo');
      }
      $data = array(
         'title' => $this->input->post('etitle'),
         'description' =>     $this->input->post('edescription'),
         'location' => $this->input->post('elocation'),
         'category' => $this->input->post('ecategory'),
         'imageUrl' => $this->input->post('old_photo'),
         'pcity' => $this->input->post('ecity'),
         'pbudget' => $this->input->post('ebudget'),
         'parea' => $this->input->post('earea'),
      );

      if (!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0) {
         $this->multipleImageUpload($id);
      }

      $result = $this->db->set($data)->where('prodId', $id)->update('products');
      redirect('products');
   }
   public function deleteProducts()
   {
      $id = $this->input->post('dprodId');
      $result = $this->db->where('prodId', $id)->get('products')->row();
      $path = $result->imageUrl;
      $featureImages = $this->db->where('productId', $id)->get('featureImages');
      if ($featureImages->num_rows() > 0) {
         foreach ($featureImages->result() as $row) {
            $fPath = $row->featureImage;
            $fimagePath = str_replace(HOME_PATH, "./", $fPath);
            if (unlink($fimagePath)) {
               $this->db->where('featureId', $row->featureId);
               $this->db->delete('featureImages');
            }
         }
         $imagePath = str_replace(HOME_PATH, "./", $path);
         if (unlink($imagePath)) {
            $this->db->where('prodId', $id);
            $this->db->delete('products');
         }
      }else{
         $imagePath = str_replace(HOME_PATH, "./", $path);
         if (unlink($imagePath)) {
            $this->db->where('prodId', $id);
            $this->db->delete('products');
         }
         
      }
      redirect('products');
   }

   public function editFeatureImage()
   {
      $mainId = $this->input->post('eId');
      $id = $this->input->post('efeatureId');
      $imageResult = $this->db->where('featureId', $mainId)->get('featureImages')->row();
      $prodId = $imageResult->productId;
      $filename = '';
      if (!empty($_FILES['featureImage']['name'])) {

         $path = $_FILES['featureImage']['name'];
         $result = $this->db->where('featureId', $this->input->post('efeatureId'))->get('featureImages')->row();
         $oldImagePath = $result->featureImage;
         $imagePath = str_replace(HOME_PATH, "./", $oldImagePath);
         if ($imagePath) {
            @unlink($imagePath);
         }
         $newName = $id . "." . pathinfo($path, PATHINFO_EXTENSION);
         $config['overwrite'] = TRUE;
         $config['upload_path'] = './uploads/images';
         $config['allowed_types'] = '*';
         $config['file_name'] = $newName;
         $this->load->library('upload', $config);
         $this->upload->initialize($config);
         if (!$this->upload->do_upload('featureImage')) {

            $error = array(
               'error' => $this->upload->display_errors()
            );
            $this->session->set_flashdata('error', 'Something went wrong. Please upload gif | jpg | png file');
         } else {

            $data = $this->upload->data();
            $filename = HOME_PATH . '/uploads/images/' . $data['file_name'];
         }
         $updateImage = array(
            'featureImage' => $filename
         );
         $insertImage = $this->db->set($updateImage)->where('featureId', $mainId)->update('featureImages');
      } else {
         $filename = $this->input->post('old_photo');
      }
      $data = array(
         'featureImage' => $filename,

      );

      $result = $this->db->set($data)->where('featureId', $id)->update('featureImages');
      redirect('edit-product/' . $prodId);
   }
   public function deleteFeaturImages()
   {
      $id = $this->input->post('dfeatureId');
      $prodId = $this->input->post('dproductId');
      $path = $this->input->post('dfeatureImage');
     
      $imagePath = str_replace(HOME_PATH, "./", $path);
      if (unlink($imagePath)) {
         $this->db->where('featureId', $id);
         $this->db->delete('featureImages');
      }else {
         $this->db->where('featureId', $this->input->post('dfeatureImage'));
         $this->db->delete('featureImages');
     };
      redirect('edit-product/' . $prodId);
   }
   public function addLocation()
   {

      $data = array(
         'location' => $this->input->post('location'),
      );
      $result = $this->db->insert('locations', $data);
      redirect('locations');
   }
   public function editLocation()
   {
      $id = $this->input->post('elocId');
      $data = array(
         'location' => $this->input->post('rlocation'),
      );
      $result = $this->db->set($data)->where('locId', $id)->update('locations');
      redirect('locations');
   }
   public function locationDelete()
   {
      $id = $this->input->post('deleteLocId');

      $this->db->where('locId', $id);
      $this->db->delete('locations');

      redirect('locations');
   }
   public function addCategory($value = '')
   {
      $data = array(
         'category' => $this->input->post('category'),
      );
      $result = $this->db->insert('categories', $data);
      redirect('categories');
   }
   public function editCategory($value = '')
   {
      $id = $this->input->post('ecatId');
      $data = array(
         'category' => $this->input->post('ecategory'),
      );
      $result = $this->db->set($data)->where('catId', $id)->update('categories');
      redirect('categories');
   }
   public function deleteCategory()
   {
      $this->db->where('catId', $this->input->post('dcatId'));
      $this->db->delete('categories');
      redirect('categories');
   }
   public function multipleImageUpload($id)
   {
      // If files are selected to upload 
      $errorUploadType = $statusMsg = '';
      if (!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0) {

         $filesCount = count($_FILES['files']['name']);
         //  print_r($filesCount);exit;
         for ($i = 0; $i < $filesCount; $i++) {
            $_FILES['file']['name']     = $_FILES['files']['name'][$i];
            $_FILES['file']['type']     = $_FILES['files']['type'][$i];
            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
            $_FILES['file']['error']     = $_FILES['files']['error'][$i];
            $_FILES['file']['size']     = $_FILES['files']['size'][$i];

            // File upload configuration 
            // $newName = $id.".".pathinfo($path, PATHINFO_EXTENSION);
            $fId = mt_rand(100000, 999999);
            $path = $_FILES['files']['name'][$i];
           
            $newName = $fId . "." . pathinfo($path, PATHINFO_EXTENSION);
            $config['overwrite'] = TRUE;
            $config['file_name'] = $newName;
            $config['upload_path'] = './uploads/images';
            $config['allowed_types'] = '*';
            //$config['max_size']    = '100'; 
            //$config['max_width'] = '1024'; 
            //$config['max_height'] = '768'; 

            // Load and initialize upload library 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            // Upload file to server 
            if ($this->upload->do_upload('file')) {
               // Uploaded file data 
               $fileData = $this->upload->data();
               $filename = HOME_PATH . '/uploads/images/' . $fileData['file_name'];
               $uploadData[$i]['productId'] = $id;
               $uploadData[$i]['imageId'] = $fId;

               $uploadData[$i]['featureImage'] = $filename;
            } else {
               $errorUploadType .= $_FILES['file']['name'] . ' | ';
            }
         }

         $errorUploadType = !empty($errorUploadType) ? '<br/>File Type Error: ' . trim($errorUploadType, ' | ') : '';
         if (!empty($uploadData)) {
            // Insert files data into the database 
            // $insert = $this->file->insert($uploadData); 
            $insert = $this->db->insert_batch('featureImages', $uploadData);
            return $insert ? true : false;
         }
      }
   }



   public function citiesAdd($value = '')
   {
      $data = array(
         'cname' => $this->input->post('cname'),
         'districtId' => $this->input->post('district'),
         'created_date' => date('Y-m-d H:i:s')
      );
      $result = $this->db->insert('cities', $data);
      redirect('cities');
   }
   public function citiesEdit($value = '')
   {
      $id = $this->input->post('editId');
      $data = array(
         'cname' => $this->input->post('ecity'),
         'districtId' => $this->input->post('edistrict'),
      );
      $result = $this->db->set($data)->where('cId', $id)->update('cities');
      redirect('cities');
   }
   public function citiesDelete()
   {
      $this->db->where('cId', $this->input->post('deleteId'));
      $this->db->delete('cities');
      redirect('cities');
   }

   public function addArea($value = '')
   {
      $data = array(
         'aname' => $this->input->post('aname'),
         'aCity' => $this->input->post('acity'),
         'created_date' => date('Y-m-d H:i:s')
      );
      $result = $this->db->insert('area', $data);
      redirect('area');
   }
   public function editArea($value = '')
   {
      $id = $this->input->post('editId');
      $data = array(
         'aname' => trim($this->input->post('aname')),
         'aCity' => $this->input->post('acity'),
      );
      $result = $this->db->set($data)->where('aId', $id)->update('area');
      redirect('area');
   }

   public function deleteArea()
   {
      $this->db->where('aId', $this->input->post('deleteId'));
      $this->db->delete('area');
      redirect('area');
   }

   public function budgetAdd($value = '')
   {
      $data = array(
         'budget_name' => $this->input->post('budget_name'),
      );
      $result = $this->db->insert('budget', $data);
      redirect('budget');
   }
   public function budgetEdit($value = '')
   {
      $id = $this->input->post('ebId');
      $data = array(
         'budget_name' => $this->input->post('ebudget_name'),
      );
      $result = $this->db->set($data)->where('bId', $id)->update('budget');
      redirect('budget');
   }
   public function budgetDelete()
   {
      $this->db->where('bId', $this->input->post('dbId'));
      $this->db->delete('budget');
      redirect('budget');
   }
}

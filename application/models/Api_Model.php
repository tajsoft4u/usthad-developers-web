<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getProductsCount()
    {
        return $this->db->get("products")->num_rows();
    }

    public function productListByLimit($limitPerPage, $mainPage, $location, $category)
    {
        $result = $this->db->order_by('prodDate', 'DESC')->get('products');
        return $result;
    }
    public function productListAllData()
    {
        $result = $this->db->order_by('prodDate', 'DESC')->get('products');
        return $result;
    }
    public function productLocFilterListByLimit($limitPerPage, $mainPage, $location)
    {
        $this->db->limit($limitPerPage, $mainPage);
        $locationData = json_decode($location);


        if ($locationData->location != null && $locationData->city != null && $locationData->area != null) {
            $result = $this->db->where('location', $locationData->location)->where('pcity', $locationData->city)->where('parea', $locationData->area)
                ->order_by('prodDate', 'DESC')->get('products');
            return $result;
        } else if ($locationData->location != null && $locationData->city != null) {
            $result = $this->db->where('location', $locationData->location)->where('pcity', $locationData->city)->order_by('prodDate', 'DESC')->get('products');
            return $result;
        } else {
            $result = $this->db->where('location', $locationData->location)->order_by('prodDate', 'DESC')->get('products');
            return $result;
        }
    }
    public function productCatFilterListByLimit($limitPerPage, $mainPage, $category)
    {
        $this->db->limit($limitPerPage, $mainPage);
        $result = $this->db->like('category', $category)->order_by('prodDate', 'DESC')->get('products');
        return $result;
    }
    public function getFeatureImages($id)
    {
        return $this->db->where('productId', $id)->get('featureImages')->result();
    }
    public function productListForFrontByLimit($limit, $start)
    {
        $this->db->limit($limit, $start);
        return $this->db->join('locations', 'locations.locId=products.location')->join('categories', 'categories.catId=products.category')->order_by('prodDate', 'DESC')->get('products');
    }

    public function getProductsById($id)
    {
        return $this->db->where('prodId', $id)->get('products')->row();
    }
    public function getProducts()
    {
        return $this->db->order_by('prodDate', 'DESC')->get('products');
    }
    public function getLocations()
    {
        return $this->db->order_by('location', 'ASC')->get('locations');
    }
    public function getCities($district)
    {
        return $this->db->where('districtId', $district)->order_by('created_date', 'DESC')->get('cities');
    }
    public function getArea($city)
    {
        return $this->db->where('aCity', $city)->order_by('aname', 'ASC')->get('area');
    }
    public function getAreasForWeb()
    {
        return $this->db->join('cities', 'cities.cId=area.aCity')->order_by('area.aname', 'ASC')->get('area');
    }

    public function getCategories()
    {
        return $this->db->order_by('catDate', 'DESC')->get('categories');
    }
    public function getCitiesForWeb()
    {
        return $this->db->join('locations', 'locations.locId=cities.districtId')->order_by('locations.location', 'ASC')->get('cities');
    }
    public function getAreaById($id)
    {

        return $this->db->where('aId', $id)->get('area')->row();
    }
}

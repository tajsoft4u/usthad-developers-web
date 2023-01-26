<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fetch_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getProductsCountWeb()
    {
        return $this->db->get("products")->num_rows();
    }
    public function getProductsCount($location)
    {
        // return $this->db->get("products")->num_rows();
        $locationData = json_decode($location);
        //  $this->db->limit($limitPerPage,$mainPage);

        if ($locationData->location != null && $locationData->city != null && $locationData->area != null && $locationData->budget == null) {
            // print_r("1");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)->get('products');
        } else if ($locationData->location != null && $locationData->city == null && $locationData->area == null && $locationData->budget == null) {
            // print_r("2");exit;
            return $this->db->where('location', $locationData->location)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area == null && $locationData->budget == null) {
            //  print_r("3");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area != null && $locationData->budget != null) {
            // print_r("4");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)
                ->where('pbudget', $locationData->budget)
                ->get('products');
        } else if ($locationData->location == null && $locationData->city == null && $locationData->area == null && $locationData->budget != null) {
            // print_r("5");exit;
            return $this->db->where('pbudget', $locationData->budget)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city == null && $locationData->area == null && $locationData->budget != null) {
            // print_r("6");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pbudget', $locationData->budget)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area == null && $locationData->budget != null) {
            // print_r("7");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pbudget', $locationData->budget)
                ->get('products');
        } else {
            // print_r("8");exit;
            return $this->db->get('products');
        }
    }

    public function productListByLimit($limitPerPage, $mainPage, $location, $category)
    {
        $locationData = json_decode($location);
        $this->db->limit($limitPerPage, $mainPage);

        if ($locationData->location != null && $locationData->city != null && $locationData->area != null && $locationData->budget == null) {
            // print_r("1");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city == null && $locationData->area == null && $locationData->budget == null) {
            // print_r("2");exit;
            return $this->db->where('location', $locationData->location)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area == null && $locationData->budget == null) {
            //  print_r("3");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area != null && $locationData->budget != null) {
            // print_r("4");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)
                ->where('pbudget', $locationData->budget)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location == null && $locationData->city == null && $locationData->area == null && $locationData->budget != null) {
            // print_r("5");exit;
            return $this->db->where('pbudget', $locationData->budget)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city == null && $locationData->area == null && $locationData->budget != null) {
            // print_r("6");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pbudget', $locationData->budget)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area == null && $locationData->budget != null) {
            // print_r("7");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pbudget', $locationData->budget)
                ->order_by('prodDate', 'DESC')->get('products');
        } else {
            // print_r("8");exit;
            return $this->db->order_by('prodDate', 'DESC')->get('products');
        }
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
        if ($locationData->location > 0 && $locationData->city > 0 && $locationData->area > 0) {
            $result = $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location > 0 && $locationData->city > 0) {
            $result = $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->order_by('prodDate', 'DESC')->get('products');
            return $result;
        } else {
            $result = $this->db->where('location', $locationData->location)
                ->order_by('prodDate', 'DESC')->get('products');
            return $result;
        }
    }
    public function productCatFilterListByLimit($limitPerPage, $mainPage, $location, $category)
    {
        $this->db->limit($limitPerPage, $mainPage);
        $locationData = json_decode($location);

        if ($locationData->location != null && $locationData->city != null && $locationData->area != null && $locationData->budget != null) {
            // print_r("1");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)
                ->where('pbudget', $locationData->budget)
                ->where('category', $category)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city == null && $locationData->area == null && $locationData->budget == null) {
            // print_r("2");exit;
            return $this->db->where('location', $locationData->location)
                ->where('category', $category)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area == null && $locationData->budget == null) {
            //  print_r("3");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('category', $category)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area != null && $locationData->budget == null) {
            //  print_r("4");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)
                ->where('category', $category)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city == null && $locationData->area == null && $locationData->budget != null) {
            // print_r("5");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pbudget', $locationData->budget)
                ->where('category', $category)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location == null && $locationData->city == null && $locationData->area == null && $locationData->budget != null) {
            //  print_r("6");exit;
            return $this->db->where('pbudget', $locationData->budget)
                ->where('category', $category)
                ->order_by('prodDate', 'DESC')->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area == null && $locationData->budget != null) {
            //  print_r("7");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('pbudget', $locationData->budget)
                ->where('category', $category)
                ->order_by('prodDate', 'DESC')->get('products');
        } else {
            // print_r("8");exit;
            return $this->db->where('category', $category)->order_by('prodDate', 'DESC')->get('products');
        }
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
    public function getBudget()
    {
        return $this->db->order_by('budget_name', 'ASC')->get('budget');
    }

    public function getCitiesForWeb()
    {
        return $this->db->join('locations', 'locations.locId=cities.districtId')->order_by('locations.location', 'ASC')->get('cities');
    }
    public function getAreaById($id)
    {

        return $this->db->where('aId', $id)->get('area')->row();
    }


    public function productCatFilterCount($location, $category)
    {

        $locationData = json_decode($location);

        if ($locationData->location != null && $locationData->city != null && $locationData->area != null && $locationData->budget != null) {
            // print_r("1");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)
                ->where('pbudget', $locationData->budget)
                ->where('category', $category)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city == null && $locationData->area == null && $locationData->budget == null) {
            // print_r("2");exit;
            return $this->db->where('location', $locationData->location)
                ->where('category', $category)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area == null && $locationData->budget == null) {
            //  print_r("3");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('category', $category)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area != null && $locationData->budget == null) {
            //  print_r("4");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('parea', $locationData->area)
                ->where('category', $category)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city == null && $locationData->area == null && $locationData->budget != null) {
            // print_r("5");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pbudget', $locationData->budget)
                ->where('category', $category)
                ->get('products');
        } else if ($locationData->location == null && $locationData->city == null && $locationData->area == null && $locationData->budget != null) {
            //  print_r("6");exit;
            return $this->db->where('pbudget', $locationData->budget)
                ->where('category', $category)
                ->get('products');
        } else if ($locationData->location != null && $locationData->city != null && $locationData->area == null && $locationData->budget != null) {
            //  print_r("7");exit;
            return $this->db->where('location', $locationData->location)
                ->where('pcity', $locationData->city)
                ->where('pbudget', $locationData->budget)
                ->where('category', $category)
                ->get('products');
        } else {
            // print_r("8");exit;
            return $this->db->where('category', $category)->get('products');
        }
    }
}

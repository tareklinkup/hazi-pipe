<?php
    class Graph extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $access = $this->session->userdata('userId');
            if($access == '' ){
                redirect("Login");
            }
            $this->load->model('Model_table', "mt", TRUE);
        }
        
        public function graph(){
            $data['title'] = "Graph";
            $data['content'] = $this->load->view('Administrator/graph/graph', $data, true);
            $this->load->view('Administrator/index', $data);
        }

        public function salesGraphData(){
            $data = [];
            $year = date('Y');
            $month = date('m');
            $dayNumber = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for($i = 1; $i <= $dayNumber; $i++){
                $date = $year . '-' . $month . '-'. sprintf("%02d", $i);
                $query = $this->db->query("
                    select ifnull(sum(sm.SaleMaster_TotalSaleAmount), 0) as sales_amount 
                    from tbl_salesmaster sm 
                    where sm.SaleMaster_SaleDate = ? 
                    group by sm.SaleMaster_SaleDate
                ", $date);

                $amount = 0.00;

                if($query->num_rows() == 0){
                    $amount = 0.00;
                } else {
                    $amount = $query->row()->sales_amount;
                }
                $sale = [sprintf("%02d", $i), $amount];
                array_push($data, $sale);
            }

            echo json_encode($data, JSON_NUMERIC_CHECK);
        }

        public function getTopProducts(){
            $products = $this->db->query("
                select 
                    p.Product_Name as product_name,
                    ifnull(sum(sd.SaleDetails_TotalQuantity), 0) as sold_quantity
                from tbl_saledetails sd
                join tbl_product p on p.Product_SlNo = sd.Product_IDNo
                group by sd.Product_IDNo
                order by sold_quantity desc
                limit 5
            ")->result();

            echo json_encode($products);
        }

        public function getTopCustomers(){
            $customers = $this->db->query("
                select 
                c.Customer_Name as customer_name,
                ifnull(sum(sm.SaleMaster_TotalSaleAmount), 0) as amount
                from tbl_salesmaster sm 
                join tbl_customer c on c.Customer_SlNo = sm.SalseCustomer_IDNo
                group by sm.SalseCustomer_IDNo
                order by amount desc 
                limit 5
            ")->result();

            echo json_encode($customers);
        }
    }
?>
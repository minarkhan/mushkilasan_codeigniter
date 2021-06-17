<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class payments extends CI_Controller {

   public $data;

   public function __construct() {

        parent::__construct();
        $this->load->model('payment_model','payment');
		$this->load->model('common_model','common_model');
        $this->data['theme'] = 'admin';
        $this->data['model'] = 'payments';
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');

    }


	public function payment_list()
  {
	  $this->common_model->checkAdminUserPermission(6);
   extract($_POST);
      $this->data['page'] = 'payment_list';
       if ($this->input->post('form_submit')) 
      {  
         $provider_id = $this->input->post('provider_id');
         $status = $this->input->post('status');
         $from = $this->input->post('from');
         $to = $this->input->post('to');
 $this->data['list'] = $this->payment->payment_filter($provider_id,$status,$from,$to);
      }
      else
      {
      $this->data['list'] = $this->payment->payment_list();
      }
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    
  }

	public function withdraw_request()
  {
	  $this->common_model->checkAdminUserPermission(6);
   extract($_POST);
      $this->data['page'] = 'withdraw_request';
       if ($this->input->post('form_submit')) 
      {  
         $provider_id = $this->input->post('provider_id');
         $status = $this->input->post('status');
         $from = $this->input->post('from');
         $to = $this->input->post('to');
      $this->data['list'] = $this->payment->payment_filter($provider_id,$status,$from,$to);
      }
      else
      {
      $this->data['list'] = $this->payment->withdraw_request();
      }
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    
  }

	public function withdraw_request_show($id)
  {
   
    $this->db->select('*');
    $this->db->from('wallet_withdraw a'); 
    $this->db->join('providers b', 'b.id=a.user_id', 'left');
    $this->db->join('withdraw_method c', 'c.wallet_withdraw_id=a.id', 'left');
    $this->db->where('a.id',$id);         
    $query = $this->db->get()->row(); 
    // echo "<pre>";
    // print_r($query);

    echo json_encode($query);


    // if($query->num_rows() != 0)
    // {
    //     echo $query->result_array();
    // }
    // else
    // {
    //     echo false;
    // }



    // $q = $this->db->get_where('wallet_withdraw', array('id' => $id))->row();

    // $provider_name = $this->db->where('id',$q['user_id'])->get('providers')->row();
    // $withdraw_detail = $this->db->where('wallet_withdraw_id',$q['id'])->get('withdraw_method')->row();
		// // echo json_encode([$q, $provider_name, $withdraw_detail]);
    // $d = array();
    // $d['wallet_withdraw'] = $this->db->get_where('wallet_withdraw', array('id' => $id))->row();
    // $d['providers'] = $this->db->where('id',$q['user_id'])->get('providers')->row();
    // $d['withdraw_method'] = $this->db->where('wallet_withdraw_id',$q['id'])->get('withdraw_method')->row();

		// echo json_encode($d);
  }

  public function withdraw_request_accept($id){
    // print_r ($withdraw = $this->db->get_where('wallet_withdraw', array('id' => $id))->row());

      $data=array(
          'withdraw_status' => 1,
      );
  
      $this->db->where('id', $id);
      $this->db->update('wallet_withdraw', $data); 
      $this->session->set_flashdata('success_message','Successfully Accept withdraw request');
      redirect(base_url('withdraw_request'));
  }
  public function withdraw_request_reject(){

  }
  public function withdraw_request_paid(){
    $id = $this->input->post('id');
    print_r ($withdraw = $this->db->get_where('wallet_withdraw', array('id' => $id))->row());

    $data=array(
      'transaction_details' => $this->input->post('transiction_note'),
      'withdraw_status' => 2,
    ); 

      $this->db->where('id', $id);
      $this->db->update('wallet_withdraw', $data); 
      $this->session->set_flashdata('success_message','Successfully Paid, Withdraw Request');
      redirect(base_url('withdraw_request'));

  }

    
    public function admin_payment()
  {
   
      $this->data['page'] = 'admin_payment';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    
  }
   public function add_payment()
  {
            $payment_details = $this->input->post();
          
            $result = $this->payment->add_payment($payment_details);                
            if($result)
            {
                 $this->session->set_flashdata('success_message','Updated successfully');    
                 
            }
            else
            {
                $this->session->set_flashdata('error_message','Something wrong, Please try again');
                 

             } 
               
           echo json_encode($result);
    
  }
	

}

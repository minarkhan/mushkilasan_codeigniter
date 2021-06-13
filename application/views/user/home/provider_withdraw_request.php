<div class="content">
  <div class="container">
    <div class="row">
      <?php $this->load->view('user/home/provider_sidemenu'); ?>
      <div class="col-xl-9 col-md-8">

        <h4 class="mb-4">Recent  Withdraw Request</h4>
        <div class="card transaction-table mb-0">
          <div class="card-body">
            <div class="table-responsive">
              <?php if (!empty($wallet_history)) { ?>
              <table id="order-summary" class="table table-center mb-0">
                <?php } else { ?>
                <table class="table table-center mb-0">
                  <?php } ?>
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Method Details</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  if(!empty($withdraw_request)) {
										$i=1;
										foreach ($withdraw_request as $rows) {
                      $amount_refund=''; 
										
									 	if(!empty($rows['reject_paid_token'])){
									 	if($rows['admin_reject_comment']=="This service amount favour for User"){
									 		$status="Amount refund to User";
									 	}else{
                      $status="Amount refund to Provider";
									 	}
									 }

										$provider_name = $this->db->where('id',$rows['user_id'])->get('providers')->row_array();
										
										
										if($rows['withdraw_status'] == 0) {
                      $withdraw_status = 'Pending';
                      $color = 'danger';
										}
										elseif($rows['withdraw_status'] == 1) {
                      $withdraw_status = 'Accepted';
                      $color = 'warning';
										}
										elseif($rows['withdraw_status'] == 2) {
                      $withdraw_status = 'Success';
                      $color = 'success';
										}

										if($provider_name != ''){
										?>
                    <tr>
                      <td><?php echo $i++ ?></td> 
											<td><?=date('d-m-Y',strtotime($rows['service_date']));?></td>
											<td><?php echo $provider_name['name'] ?></td>
											<td>$<?php echo $rows['amount']?></td>
											<td><?php echo $rows['request_payment']?></td>
											<td width='10%'>
											<code class="text-dark">
												<?php
													$withdraw_detail = $this->db->where('wallet_withdraw_id',$rows['id'])->get('withdraw_method')->row_array();
													if($rows['request_payment'] == 'benifitpay'){
												?>
														<p>
															<span>
															Phone: <b><?php echo $withdraw_detail['benifit_phone']; ?>,</b> 
															Email.: <b><?php echo $withdraw_detail['benifit_email']; ?></b><br>
															IBAN No.: <b><?php echo $withdraw_detail['account_iban']; ?>,</b>
															</span>
														</p>
												<?php		
													} elseif($rows['request_payment'] == 'paypal'){
												?>
														<p>
															<span>
															Account: <b><?php echo $withdraw_detail['paypal_account']; ?>,</b> 
															Email: <b><?php echo $withdraw_detail['paypal_email_id']; ?></b>
															</span>
														</p>
												<?php
													}elseif($rows['request_payment'] == 'bank') {
												?>
														<p>
															<span>
															Name: <b><?php echo $withdraw_detail['account_holder_name']; ?>,</b> 
															A/C No.: <b><?php echo $withdraw_detail['account_number']; ?>,</b><br>
															IBAN No.: <b><?php echo $withdraw_detail['account_iban']; ?>,</b> 
															Bank Name: <b><?php echo $withdraw_detail['bank_name']; ?>,</b><br>
															Bank Address: <b><?php echo $withdraw_detail['bank_address']; ?>,</b> 
															IFSC Code: <b><?php echo $withdraw_detail['ifsc_code']; ?>,</b> <br>
															Pancard No: <b><?php echo $withdraw_detail['pancard_no']; ?>,</b> 
															Routing Rumber: <b><?php echo $withdraw_detail['routing_number']; ?></b>
															</span>
														</p>
												<?php	
													}
												?>
											</code>
											</td>
											<td><span class="badge bg-<?php echo $color; ?>-light"><?php echo $withdraw_status?></span>
                      <?php echo $status?>
                      </td>
										</tr>
										
                  <?php
									} } } else {
                  ?>
									<tr>
										<td colspan="7">
											<div class="text-center text-muted">No records found</div>
										</td>
									</tr>
									<?php } ?>
                </tbody>

                </table>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>


  <?php
  $query = $this->db->query("select * from system_settings WHERE status = 1");
  $result = $query->result_array();
  $stripe_option = '1';
  $publishable_key = '';
  $live_publishable_key = '';
  $logo_front = '';
  foreach ($result as $res) {
    if ($res['key'] == 'stripe_option') {
      $stripe_option = $res['value'];
    }
    if ($res['key'] == 'publishable_key') {
      $publishable_key = $res['value'];
    }
    if ($res['key'] == 'live_publishable_key') {
      $live_publishable_key = $res['value'];
    }

    if ($res['key'] == 'logo_front') {
      $logo_front = $res['value'];
    }
  }

  if ($stripe_option == 1) {
    $stripe_key = $publishable_key;
  } else {
    $stripe_key = $live_publishable_key;
  }

  if (!empty($logo_front)) {
    $web_log = base_url() . $logo_front;
  } else {
    $web_log = base_url() . 'assets/img/logo.png';
  }
  ?>

  <input type="hidden" id="stripe_key" value="<?= $stripe_key; ?>">
  <input type="hidden" id="logo_front" value="<?= $web_log; ?>">
  <input type="hidden" id="token" value="<?= $this->session->userdata('chat_token'); ?>">


  <!--- Withdraw details modal--->
  <div class="modal" id="withdraw_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2
          class="text-center"><?php echo (!empty($user_language[$user_selected]['lg_withdraw_amount'])) ? $user_language[$user_selected]['lg_withdraw_amount'] : $default_language['en']['lg_withdraw_amount']; ?></h2>
        <div class="modal-body">
          <form id="bank_details" method="post" action="#">
            <div class="paypal_details">
              <div class="form-group">
                <label><?php echo (!empty($user_language[$user_selected]['lg_paypal_id'])) ? $user_language[$user_selected]['lg_paypal_id'] : $default_language['en']['lg_paypal_id']; ?></label>
                <input class="form-control" type="text" name="paypal_id"
                       value="<?= (!empty($bank_account['paypal_account'])) ? $bank_account['paypal_account'] : ''; ?>"
                       id="paypal_id">
                <span class="paypal_id_error"></span>
              </div>
              <div class="form-group">
                <label><?php echo (!empty($user_language[$user_selected]['lg_paypal_email_id'])) ? $user_language[$user_selected]['lg_paypal_email_id'] : $default_language['en']['lg_paypal_email_id']; ?></label>
                <input class="form-control" type="text" name="paypal_email_id"
                       value="<?= (!empty($bank_account['paypal_email_id'])) ? $bank_account['paypal_email_id'] : ''; ?>"
                       id="paypal_email_id">
                <span class="paypal_email_id_error"></span>
              </div>
            </div>
            <div class="bank_details">
              <div class="form-group">
                <label>
                  Bank Name
                </label>
                <input class="form-control" type="text" name="bank_name1"
                       value="<?= (!empty($bank_account['bank_name1'])) ? $bank_account['bank_name1'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>Bank Address</label>
                <input class="form-control" type="text" name="bank_address"
                       value="<?= (!empty($bank_account['bank_address'])) ? $bank_account['bank_address'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>Account No</label>
                <input class="form-control" type="text" name="account_no"
                       value="<?= (!empty($bank_account['account_number'])) ? $bank_account['account_number'] : ''; ?>"
                       id="account_no">
                <span class="account_no_error"></span>
              </div>
              <div class="form-group">
                <label>IFSC Code</label>
                <input class="form-control" type="text" name="ifsc_code"
                       value="<?= (!empty($bank_account['account_ifsc'])) ? $bank_account['account_ifsc'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>Sort Code</label>
                <input class="form-control" type="text" name="sort_code"
                       value="<?= (!empty($bank_account['sort_code'])) ? $bank_account['sort_code'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>Routing No</label>
                <input class="form-control" type="text" name="routing_number"
                       value="<?= (!empty($bank_account['routing_number'])) ? $bank_account['routing_number'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>IBAN No</label>
                <input class="form-control" type="text" name="account_iban"
                       value="<?= (!empty($bank_account['account_iban'])) ? $bank_account['account_iban'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>Pan No</label>
                <input class="form-control" type="text" name="pancard_no"
                       value="<?= (!empty($bank_account['pancard_no'])) ? $bank_account['pancard_no'] : ''; ?>">
              </div>
            </div>
            <div class="razorpay_details">
              <div class="form-group">
                <label>
                  Name
                </label>
                <input class="form-control" type="text" name="name"
                       value="<?= (!empty($bank_account['name'])) ? $bank_account['name'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>
                  Email ID
                </label>
                <input class="form-control" type="text" name="email"
                       value="<?= (!empty($bank_account['email'])) ? $bank_account['email'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>
                  Contact No
                </label>
                <input class="form-control" type="text" name="contact"
                       value="<?= (!empty($bank_account['contact'])) ? $bank_account['contact'] : ''; ?>">
              </div>

              <div class="form-group">
                <label>
                  Card No
                </label>
                <input class="form-control" type="text" name="cardno"
                       value="<?= (!empty($bank_account['cardno'])) ? $bank_account['cardno'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>
                  Card Name
                </label>
                <input class="form-control" type="text" name="cardname"
                       value="<?= (!empty($bank_account['cardname'])) ? $bank_account['cardname'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>
                  Bank Name
                </label>
                <input class="form-control" type="text" name="bank_name" value="">
              </div>
              <div class="form-group">
                <label>
                  IFSC Code
                </label>
                <input class="form-control" type="text" name="ifsc"
                       value="<?= (!empty($bank_account['ifsc'])) ? $bank_account['ifsc'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>
                  Account No
                </label>
                <input class="form-control" type="text" name="accountnumber"
                       value="<?= (!empty($bank_account['accountnumber'])) ? $bank_account['accountnumber'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>
                  Payment Mode
                </label>
                <select class="form-control" name="mode">
                  <option value="">Select Payment Mode</option>
                  <option value="NEFT">NEFT</option>
                  <option value="RTGS">RTGS</option>
                  <option value="IMPS">IMPS</option>
                  <option value="UPI">UPI</option>
                </select>
              </div>
              <div class="form-group">
                <label>
                  Payment Purpose
                </label>
                <select class="form-control" name="purpose">
                  <option value="">Select Payment Purpose</option>
                  <option value="refund">refund</option>
                  <option value="cashback">cashback</option>
                  <option value="payout" selected="">payout</option>
                </select>
              </div>
            </div>
            <input type="hidden" name="amount" id="stripe_amount">
            <input type="hidden" name="payment_type" id="payment_types">
            <!--<input type="hidden" id="wallet_amount" value="<?php //echo (int)$total_amount; ?>">-->
            <button type="submit" class="btn btn-primary btn-block withdraw-btn1">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>


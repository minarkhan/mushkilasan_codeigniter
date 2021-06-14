<?php
	$providers = $this->db->get('providers')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Withdraw request</h3>
				</div>
				<div class="col-auto text-right">
					<a class="btn btn-white filter-btn mr-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>admin/payments/payment_list" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    

			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Provider</label>
								<select class="form-control" name="provider_id">
									<option value="">Select Provider</option>
									<?php foreach ($providers as $pro) { ?>
									<option value="<?=$pro['id']?>"><?php echo $pro['name']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Status</label>
								<select class="form-control" name="status">
									<option value="">Select Status</option>
									<option value="1">Pending</option>
									<option value="2">Inprogress</option>
									<option value="3">Completed (Provider)</option>
									<option value="6">Accepted</option>
									<option value="5">Rejected</option>
									<option value="7">Cancelled (Provider)</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>From Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="from">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>To Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="to">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<button class="btn btn-primary btn-block" name="form_submit" value="submit" type="submit">Submit</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</form>
		<!-- /Search Filter -->

		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 payment_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Provider</th>
                                        <th>Amount</th>
                                        <th>Currency</th>
                                        <th>Payment Method</th>
                                        <th>Method Details</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($list)) {
										$i=1;
										foreach ($list as $rows) {
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
                                            $status = 'Pending';
											$color = 'danger';
										}
										elseif($rows['withdraw_status'] == 1) {
                                            $status = 'Accepted';
											$color = 'warning';
										}
										elseif($rows['withdraw_status'] == 2) {
                                            $status = 'Success';
											$color = 'success';
										}

										if($provider_name != ''){
										?>
                                        <tr>
											<td><?php echo $i++ ?></td> 
											<td><?=date('d-m-Y',strtotime($rows['service_date']));?></td>
											<td><?php echo $provider_name['name'] ?></td>
											<td>$<?php echo $rows['amount']?></td>
											<td><?php echo $rows['currency_code']?></td>
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
											<td>
												<span class="badge bg-<?php echo $color; ?> text-white"><?php echo $status?></span>
											
											</td>
											<td>
											
											<?php 
												if($rows['withdraw_status'] == 0) {
											?>
													<a href="<?php echo base_url('withdraw_request_accept/' . $rows['id']); ?>">
													<span class="btn btn-primary">
													accept
													</span>
													</a>
													
													<!-- <span class="btn btn-danger">Reject</span> -->
											<?php	} 
												elseif($rows['withdraw_status'] == 1) {
											?>
												<form method="post" action="<?php echo base_url('withdraw_request_paid'); ?>">
													<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
													<input type="hidden" name="id" value="<?php echo $rows['id']; ?>">
													<span >
														<textarea style="width: 150px;" rows="1" placeholder="Transiction note" class="form-control mb-1"  name="transiction_note"></textarea>
													</span>
													<span class="float-right">
														<input class="btn btn-success" type="submit" value="Paid">
													</span>
												</form>
											<?php	} else{ ?>
												<code class="text-dark">
													<p><?php echo $rows['transaction_details'] != null ? '<b>Note:</b>' . $rows['transaction_details'] : 'Transiction Success' ; ?></p>
												</code>
											<?php }
											?>
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
</div>
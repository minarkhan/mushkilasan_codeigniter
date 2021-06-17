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
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>View</th>
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
											<td><?=date('d-m-Y',strtotime($rows['created_at']));?></td>
											<td><?php echo $provider_name['name'] ?></td>
											<td>$<?php echo $rows['amount']?></td>
											<td><?php echo $rows['currency_code']?></td>
											<td><?php echo $rows['request_payment']?></td>
											<!-- <td width='10%'>
											<code class="text-dark">
												<?php
													// $withdraw_detail = $this->db->where('wallet_withdraw_id',$rows['id'])->get('withdraw_method')->row_array();
													// if($rows['request_payment'] == 'benifitpay'){
												?>
														<p>
															<span>
															Phone: <b><?php //echo $withdraw_detail['benifit_phone']; ?>,</b> 
															Email: <b><?php //echo $withdraw_detail['benifit_email']; ?></b><br>
															IBAN No: <b><?php //echo $withdraw_detail['account_iban']; ?>,</b>
															</span>
														</p>
												<?php		
													// } elseif($rows['request_payment'] == 'paypal'){
												?>
														<p>
															<span>
															Account: <b><?php //echo $withdraw_detail['paypal_account']; ?>,</b> 
															Email: <b><?php //echo $withdraw_detail['paypal_email_id']; ?></b>
															</span>
														</p>
												<?php
													// }elseif($rows['request_payment'] == 'bank') {
												?>
														<p>
															<span>
															Name: <b><?php //echo $withdraw_detail['account_holder_name']; ?>,</b> 
															A/C No: <b><?php //echo $withdraw_detail['account_number']; ?>,</b><br>
															IBAN No: <b><?php //echo $withdraw_detail['account_iban']; ?>,</b> 
															Bank Name: <b><?php //echo $withdraw_detail['bank_name']; ?>,</b><br>
															Bank Address: <b><?php //echo $withdraw_detail['bank_address']; ?>,</b> 
															IFSC Code: <b><?php //echo $withdraw_detail['ifsc_code']; ?>,</b> <br>
															Pancard No: <b><?php //echo $withdraw_detail['pancard_no']; ?>,</b> 
															Routing Rumber: <b><?php //echo $withdraw_detail['routing_number']; ?></b>
															</span>
														</p>
												<?php	
													//}
												?>
											</code>
											</td> -->
											<td>
												<span class="badge bg-<?php echo $color; ?> text-white"><?php echo $status?></span>
											
											</td>
											<td class="request_details" data-id="<?php echo $rows['id']; ?>"><span class="btn btn-success">View</span></td>
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
											<form class="form-inline" method="post" action="<?php echo base_url('withdraw_request_paid'); ?>">
												<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
												<input type="hidden" name="id" value="<?php echo $rows['id']; ?>">
												<div class="form-group pt-1 mb-2 mr-2">
													<!-- <label for="staticEmail2" class="sr-only">Email</label> -->
													<textarea placeholder="Transiction note" class="form-control mb-1"  name="transiction_note"></textarea>
													<!-- <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="email@example.com"> -->
												</div>
												<!-- <div class="form-group mx-sm-3 mb-2">
													<label for="inputPassword2" class="sr-only">Password</label>
													<input type="password" class="form-control" id="inputPassword2" placeholder="Password">
												</div> -->
												<input class="btn btn-danger" type="submit" value="Paid">
												<!-- <button type="submit" class="btn btn-primary mb-2">Confirm identity</button> -->
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
<div id="myModal" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Withdraw request details</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<!-- <p>No data found</p> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function(){
        $(".request_details").click(function(){
			var a = $(this).attr("data-id");
		$.ajax({
			type:'GET',
			url: '<?php echo base_url('withdraw_request_show/');?>' + a,
			dataType : 'json',
			success: function(data) {
				// console.log(data);
				var date = new Date(data['req_date'].replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));

				var content = '';
				content += '<table class=\"table table-bordered\">\n' ;
				content += '  <thead>\n' ;
				content += '    <tr class=\"border-bottom\">\n' ;
				content += '      <th scope=\"col\">Date</th>\n' ;
				content += '      <td scope=\"row\">'+ date +'</td>\n' ;
				content += '    </tr>\n' ;
				content += '    <tr class=\"border-bottom\">\n' ;
				content += '      <th scope=\"col\">Provider</th>\n' ;
				content += '      <td scope=\"row\">'+ data['name'] + '</td>\n' ;
				content += '    </tr>\n' ;
				content += '    <tr class=\"border-bottom\">\n' ;
				content += '      <th scope=\"col\">Amount</th>\n' ;
				content += '      <td scope=\"row\">$'+ data['amount'] + '.' +data['currency_code'] +'</td>\n' ;
				content += '    </tr>\n' ;
				content += '    <tr class=\"border-bottom\">\n' ;
				content += '      <th scope=\"col\">Currency</th>\n' ;
				content += '      <td scope=\"row\">'+ data['currency_code'] +'</td>\n' ;
				content += '    </tr>\n' ;
				content += '    <tr class=\"border-bottom\">\n' ;
				content += '      <th scope=\"col\">Payment Method</th>\n' ;
				content += '      <td scope=\"row\">'+ data['request_payment'] +'</td>\n' ;
				content += '    </tr>\n' ;
				content += '    <tr class=\"border-bottom\">\n' ;
				content += '      <th scope=\"col\">Method Details</th>\n' ;
				if(data['request_payment'] == 'benifitpay'){
					content += '      <td scope=\"row\">'+
									'<p>'+
										'<span>'+
										'Phone: <b>'+ data['benifit_phone'] +',</b> '+
										'Email: <b>'+ data['benifit_email'] +',</b><br>'+
										'IBAN No: <b>'+ data['account_iban'] +',</b> '+
										'</span>'+
									'</p>'+
								'</td>\n' ;
				} else if(data['request_payment'] == 'paypal'){
					content += '      <td scope=\"row\">'+
									'<p>'+
										'<span>'+
										'Account: <b>'+ data['paypal_account'] +',</b> '+
										'Email: <b>'+ data['paypal_email_id'] +',</b><br>'+
										'</span>'+
									'</p>'+
								'</td>\n' ;
				} else if(data['request_payment'] == 'bank'){
					content += '      <td scope=\"row\">'+
									'<p>'+
										'<span>'+
										'Name: <b>'+ data['account_holder_name'] +',</b> '+
										'A/C No: <b>'+ data['account_number'] +',</b><br>'+
										'IBAN No: <b>'+ data['account_iban'] +',</b> '+
										'Bank Name: <b>'+ data['bank_name'] +',</b><br>'+
										'Bank Address: <b>'+ data['bank_address'] +',</b> '+
										'IFSC Code: <b>'+ data['ifsc_code'] +',</b> <br>'+
										'Pancard No: <b>'+ data['pancard_no'] +',</b> '+
										'Routing Rumber: <b>'+ data['routing_number'] +'</b>'+
										'</span>'+
									'</p>'+
								'</td>\n' ;
				}
				content += '    </tr>\n' ;
				content += '    <tr class=\"border-bottom\">\n' ;
				content += '      <th scope=\"col\">Status</th>\n' ;
				if(data['withdraw_status'] == 0) {
					content += '      <td scope=\"row\"> Pending </td>\n' ;
				}
				else if(data['withdraw_status'] == 1) {
					content += '      <td scope=\"row\">Accepted</td>\n' ;
				}
				else if(data['withdraw_status'] == 2) {
					content += '      <td scope=\"row\">Success</td>\n' ;
				}
				content += '    </tr>\n' ;
				content += '  </thead>\n' ;
				content += '</table>' ;

				$("#myModal").modal('show');
				$('.modal-body').html('');
				$('.modal-body').append(content);

			},
            error: function(data) {
                console.log('minar error');
            },

		});
	});
});
</script>

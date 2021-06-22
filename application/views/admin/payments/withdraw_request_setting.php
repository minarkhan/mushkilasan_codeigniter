<div class="page-wrapper">
	<div class="content container-fluid">
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title border-bottom pb-2 mb-3">Withdraw request settings</h3>
					<form action="<?php echo base_url('withdraw_request_set'); ?>" method="post">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

						<div class="card" style="width: 18rem;">
							<div class="card-body">
								<h5 class="card-title">Days Settings</h5>
								<div class="d-flex">
								<input type="hidden" name="id" value="<?php echo (!empty($days)) ? $days->id : ''; ?>">
								<input type="text" name="withdraw_days" class="form-control mb-2" value="<?php echo (!empty($days)) ? $days->days : ''; ?>">
								<span class="ml-3 mt-2">Days</span>
								</div>
								<p class="card-text">Provider can be withdraw their amount after following days.</p>
								<input type="submit" class="btn btn-primary" value="Update">
							</div>
						</div>
					</form>
				</div>
				
			</div>
		</div>
		<!-- /Page Header -->
	</div>
</div>

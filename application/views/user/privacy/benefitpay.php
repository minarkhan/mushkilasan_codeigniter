<?php
$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
?>
<div class="breadcrumb-bar">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumb-title">
          <h2>Benefitpay Page</h2>
<!--					<h2>--><?php //echo (!empty($user_language[$user_selected]['lg_Privacy_Policy'])) ? $user_language[$user_selected]['lg_Privacy_Policy'] : $default_language['en']['lg_Privacy_Policy']; ?><!--</h2>-->
				</div>
			</div>
			<div class="col-auto float-right ml-auto breadcrumb-menu">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Privacy'])) ? $user_language[$user_selected]['lg_Privacy'] : $default_language['en']['lg_Privacy']; ?></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>

<div class="">
  <div class="first col-md-7">
    <h2>Fill Form to Generate Sadded Invoice</h2>
    <form id="create_form" method="post" name="create_form">
      <div class="form-group">
        <label>URL *:</label>
        <input id="url" name="url" placeholder='URL' type='text' class="form-control" required="" value="https://eps-net-uat.sadadbh.com/">
      </div>
      <div class="form-group">
        <label>Branch ID *:</label>
        <input id="branch_id" name="branch_id" placeholder='Branch Key' type='text' class="form-control" required="" value="546">
      </div>
      <div class="form-group">
        <label>Vendor ID *:</label>
        <input id="vendor_id" name="vendor_id" placeholder='Vendor Key' type='text' class="form-control" required="" value="474">
      </div>
      <div class="form-group">
        <label>Terminal ID *:</label>
        <input id="terminal_id" name="terminal_id" placeholder='Terminal Key' type='text' class="form-control" required="" value="640">
      </div>
      <div class="form-group">
        <label>API Key *:</label>
        <input id="api_key" name="api_key" placeholder='API Key' type='text' class="form-control" required="" value="44926353-631e-4e57-89cf-0134922bdd0f" >
      </div>
      <div class="form-group">
        <label>Mode *:</label>
        <select onchange="getval(this);" class="form-control" id="mode" required="" name="mode">
          <option value="">Select mode</option>
          <option value="sms">Sms</option>
          <option value="email">Email</option>
          <option value="online">Online</option>
        </select>
      </div>
      <div class="form-group">
        <label>Customer Name *:</label>
        <input id="name" name="customer_name" placeholder='Customer Name' type='text' class="form-control" required="" value="mehedi609">
      </div>
      <div class="form-group">
        <label>Email :</label>
        <input id="email" name="email" placeholder='Valid Email Address' type='email' class="form-control" value="mehedi609@gmail.com">
      </div>
      <div class="form-group">
        <label>Msisdn :</label>
        <input id="msisdn" name="msisdn" placeholder='Msisdn' type='text' class="form-control" value="97334554180">
      </div>
      <div class="form-group">
        <label>Amount *:</label>
        <input id="amount" name="amount" placeholder='Amount' type='number' value="100" class="form-control" required="">
      </div>
      <div class="form-group">
        <label>Description :</label>
        <input id="description" name="description" placeholder='Description' type='text' class="form-control" value="demo description">
      </div>
      <div class="form-group">
        <label class="control-label">Date *:</label>
        <div class='input-group date'>
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
          </div>
          <input type='text' class="form-control" name="date"  id='date'/>
        </div>
      </div>
      <div class="form-group" id="external_reference">
        <label>External Reference :</label>
        <input id="external_reference" name="external_reference" placeholder='External Reference' type='text' class="form-control" value="">
      </div>
      <div class="form-group" id="success_url_fg">
        <label>Success URL :</label>
        <input id="success_url" name="success_url" placeholder='Success URL' type='text' class="form-control" value="">
      </div>
      <div class="form-group" id="error_url_fg">
        <label>Error URL :</label>
        <input id="error_url" name="error_url" placeholder='Error URL' type='text' class="form-control" value="">
      </div>
      <input id='btn' name="submit" type='submit' value='Submit' class="btn btn-primary">
    </form>
    <p style="padding: 5px 5px 5px 0px;"><a id="payment_href">Click here to make payment</a></p>
  </div>

  <div class="first col-md-4">
    <h2>Fill Form to Get Sadded Invoice Status</h2>
    <form action="get_invoice_status.php" id="get_form" method="post" name="get_form">
      <div class="form-group">
        <label>URL *:</label>
        <input id="url" name="url" placeholder='URL' type='text' class="form-control" required=""
               value="https://eps-net-uat.sadadbh.com/">
      </div>
      <div class="form-group">
        <label>Branch ID *:</label>
        <input id="branch_id" name="branch_id" placeholder='Branch Key' type='text' class="form-control" required=""
               value="">
      </div>
      <div class="form-group">
        <label>Vendor ID *:</label>
        <input id="vendor_id" name="vendor_id" placeholder='Vendor Key' type='text' class="form-control" required=""
               value="">
      </div>
      <div class="form-group">
        <label>Terminal ID *:</label>
        <input id="terminal_id" name="terminal_id" placeholder='Terminal Key' type='text' class="form-control"
               required="" value="">
      </div>
      <div class="form-group">
        <label>API Key *:</label>
        <input id="api_key" name="api_key" placeholder='API Key' type='text' class="form-control" required="" value="">
      </div>
      <div class="form-group">
        <label>Transaction Reference :</label>
        <input id="transaction_reference" name="transaction_reference" placeholder='Transaction Reference' type='text'
               class="form-control" value="">
      </div>
      <input id='btn' name="submit" type='submit' value='Submit' class="btn btn-primary">
    </form>
    <p style="padding: 5px 5px 5px 0px;"><a></a></p>
  </div>
</div>
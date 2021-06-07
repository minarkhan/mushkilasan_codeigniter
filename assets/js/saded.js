$('#date').datetimepicker({
  format: 'DD-MM-YYYY',
  icons: {
    up: "fas fa-angle-up",
    down: "fas fa-angle-down",
    next: 'fas fa-angle-right',
    previous: 'fas fa-angle-left'

  }
});

var href = $('#payment_href');
if (href) {
  href.hide();
}

function getval(sel) {
  $('#success_url_fg').hide();
  $('#error_url_fg').hide();
  $('#success_url').removeAttr('required');
  $('#error_url').removeAttr('required');
  if (sel.value == "sms") {
    $("#msisdn").attr("required", "true");
    $('#email').removeAttr('required');
  } else if (sel.value == "email") {
    $("#email").attr("required", "true");
    $('#msisdn').removeAttr('required');
  } else if (sel.value == "online") {
    $('#success_url_fg').show();
    $('#error_url_fg').show();
    $("#success_url").attr("required", "true");
    $("#error_url").attr("required", "true");
  }
}

$(document).ready(function () {
  $('#success_url_fg').hide();
  $('#error_url_fg').hide();

  var create_form = $('#create_form');
  create_form.submit(function (event) {
    event.preventDefault();
    var url = create_form.attr('action');
    console.log(url);
    console.log(create_form.serialize());
    $.ajax({
      type: "POST",
      url: 'http://localhost/mushkilasan/user/privacy/generate_invoice',
      dataType: 'json',
      data: create_form.serialize(),
      success: function (data) {
        // data = JSON.parse(data);
        if (data['error-code'] && data['error-code'] > 0) {
          var href = $('#payment_href');
          if (href) {
            href.hide();
          }
          alert(data['error-message']);
        }
        if ((data['error-code'] == 0) && data['payment-url']) {
          var href = $('#payment_href');
          if (href) {
            href.show();
            href.attr("href", data['payment-url']);
            href.attr("target", "_blank");
          }
        }
      }
    });
  });

  var get_form = $('#get_form');
  get_form.submit(function (event) {
    event.preventDefault();
    var url = get_form.attr('action');
    console.log(url);
    $.ajax({
      type: "POST",
      url: url,
      data: get_form.serialize(),
      dataType: 'json',
      success: function (data) {
        // data = JSON.parse(data);
        if (data['error-message']) {
          alert(data['error-message']);
        }
      }
    });
  });
});
var responsedata = ''
var currentUrl = jQuery(location).attr('href').replace(/\/$/, "")
currentUrl = currentUrl.split('#')[0]
currentUrl = currentUrl.replace(/&amp;/g, '&')
var parts = currentUrl.split('/')
var payment_id = parts.pop() || parts.pop();

var env = currentUrl.split('/').slice(-2)[0]

var baseUrl = ''

var currentUrl_url = new URL(currentUrl)


var l = currentUrl.length - 1

var d = new Date()

var month = d.getMonth() + 1
var day = d.getDate()
console.log("enviornment" + env)
switch (env) {
  case 'dev':
    baseUrl = 'https://api-dev.julofinance.com/'
    //baseUrl = 'http://2388d4f9.ngrok.io/'
    break
  case 'staging':
    baseUrl = 'https://api-staging.julofinance.com/'
    break
  case 'prod':
    baseUrl = 'https://api.julofinance.com/'
    break
  default:
    baseUrl = 'https://api.julofinance.com/'
}

function hideloader() {
 
  var interval = setInterval(function () {
    if (jQuery('#preloader').length) {
      jQuery('#preloader').css('display', 'none')
      clearInterval(interval)
    }
  }, 100)
}


var fetchDetails = function (value) {
  jQuery.ajax({
    url: baseUrl +
      'api/v1/payment-info/' + payment_id,
    type: 'GET',

    success: function (response) {
   
	  
	  if(jQuery('#summary_content').length){
	  
     
      jQuery.each(response, function (i, item) {
        jQuery.each(item, function (i, item) {
         
         if (i == 'payment_number') {
            $(".Desc").html('Angsuran ke '+response.payment.payment_number+' JULO')
         }
          if (i == 'payment_details') {
            var str = '';

            if (item.length > 1) {
                jQuery('#pymentdets').show()
            } else {
                jQuery('#pymentdets').hide()
            }
            for(k = 0; k <item.length-1;k++) {
              txt = '<br>';
              nomor_txt = '';
              if (item[k].bank_code == '014' || item[k].bank_code == '013') {
                  txt = '<br><b>PT. JULO TEKNOLOGI FINANSIAL</b><br>'
              } 
              if (item[k].bank_code == null) {
                  nomor_txt = 'Nomor Rekening'
              } else {
                  nomor_txt = 'Nomor Virtual Account'
              }
              virtual_account = item[k].virtual_account
              var v_account = virtual_account.replace( /(?<=^(.{4})+)(?!$)/g, ' ' )

              str = str + "<div class='accordion-group'><div class='accordion-heading'>"+
                "<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse"+k+"'>"+
                  ""+item[k].payment_method_name+""+
                "</a>"+
                "</div>"+
                "<div id='collapse"+k+"' class='accordion-body collapse'>"+
                "<div class='accordion-inner'>"+item[k].payment_method_name+":"+txt+"<br>"+nomor_txt+":<br>"+
                "<b>"+v_account+"</b>"+
                "</div>"+
                "</div>"+
                "</div>"

             
              
            }
            $(".appendDiv").html(str)
          }
          if (jQuery('.' + i).length) {
            jQuery('.' + i).each(function () {
              jQuery(this).html(item)
            })
          }
        })
      })
     
      
	  }else {
	  
      jQuery('#summary_content').hide()
     
      jQuery('#error').show()
	  }
    },
	complete: function(){
		$("#preloader").hide();
	},
    error: function () {
     
      jQuery('#summary_content').hide()
     
      jQuery('#error').show()
      
    }
  })
}


jQuery(document).ready(function () {
  

  fetchDetails()
  
  
 
})

function redirects () {
  window.location = "https://www.julo.co.id/cara-membayar.html";
}


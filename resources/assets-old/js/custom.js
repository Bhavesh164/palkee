//  $(function() {
//   // var current = window.location.href;
//    var current = window.location.href.split('?')[0];
//   
//    $('ul li a').each(function() {
//      var $this = $(this);   
//      
//      if ($this.attr('href') == current) {
//        $this.parent().addClass('active');
//        var active_ul = $this.parent().closest('ul');
//        if(active_ul.hasClass('submenu'))
//        {
//            active_ul.addClass('show');
//        }
//        var active_li = $this.parent().closest('ul').closest('li');
//        active_li.addClass('active');
//        
////        if(active_li.hasClass("dropdown_single")) {
////           active_li.addClass('active_single_menu');
////        }
////        else {
////          active_li.addClass('active');
////        }
//        
//        //$this.parent().closest('ul').closest('li').css({"display": "block"});
//      }
//    })
//  });
  
$(function () {
              for (var i = window.location.href.split('?')[0], o = $(".menu-categories li a").filter(function () {
                      return this.href == i;
              }).addClass("").parent().addClass("active");;) {
                      if (!o.is("li")) break;
                      o = o.parent("").addClass("show").parent("").addClass("active");
              }
});
function numaric(e, object) {
              
                var KeyID = (window.event) ? event.keyCode : e.which;
                if ((KeyID >= 65 && KeyID <= 90) || (KeyID >= 100 && KeyID <= 122) || (KeyID >= 33 && KeyID <= 47) || (KeyID >= 58 && KeyID <= 64) || (KeyID >= 91 && KeyID <= 95) || (KeyID >= 123 && KeyID <= 126)) {
                    return false;
                }
            }
function float(e, object) {
              
                var KeyID = (window.event) ? event.keyCode : e.which;
                console.log(KeyID);
                if ((KeyID >= 65 && KeyID <= 90) || (KeyID >= 100 && KeyID <= 122) || (KeyID >= 33 && KeyID <= 45) || (KeyID==47)|| (KeyID >= 58 && KeyID <= 64) || (KeyID >= 91 && KeyID <= 95) || (KeyID >= 123 && KeyID <= 126)) {
                    return false;
                }
            }
            
function getregions(e)
{
   // alert("country change");
    var country_id = e.value;
    if(country_id)
    {
        var token = $('meta[name="csrf-token"]').attr('content');
        
        $.post(BASE_URL+"/admin/common/location", {country_id: country_id, type: 'region',_token: token}, function (response) {
            if (response.success == 1) {
                var data = response.data;
                $('#region').empty();
                 var option = new Option('Select Region','', true, true);
                 $('#region').append(option);
                // $('#region').append('<option value="">Select Region</option>');
                $('#city').empty();
                var option = new Option('Select City','', true, true);
                $('#city').append(option);
                //$('#city').append('<option value="">Select City</option>');
                $.each(data, function (key, val) {
                    var option = new Option(val.name, val.regionId,false,false);
                    $('#region').append(option);
                    //$('#region').append('<option id="' + val.regionId + '" value="' + val.regionId + ' " title="' + val.name + '">' + val.name + '</option>');
                });
            } else {
                $('#region').empty().append(new Option('Select Region','', true, true));
                $('#city').empty().append(new Option('Select City','', true, true));
                
//                $('#region').empty().append('<option value="">Select Region</option>');
//                $('#city').empty().append('<option value="">Select City</option>');
            }
        }, "json");
    }else
    {  
        $('#region').empty().append(new Option('Select Region','', true, true));
        $('#city').empty().append(new Option('Select City','', true, true));
//        $('#region').empty().append('<option value="">Select Region</option>');
//        $('#city').empty().append('<option value="">Select City</option>');
    }
}
function getcities(e)
{
   // alert("region change");
    var region_id = e.value;
    if(region_id)
    {
        var token = $('meta[name="csrf-token"]').attr('content');
        $.post(BASE_URL+"/admin/common/location", {region_id: region_id, type: 'cities',_token: token}, function (response) {
            if (response.success == 1) {
                var data = response.data;
                
                $('#city').empty();
                var option = new Option('Select City','', true, true);
                $('#city').append(option);
                
//                $('#city').empty();
//                $('#city').append('<option value="">Select City</option>');
                $.each(data, function (key, val) {
                    var option = new Option(val.name, val.cityId,false,false);
                    $('#city').append(option);
                    
                    //$('#city').append('<option id="' + val.cityId + '" value="' + val.cityId + ' " title="' + val.name + '">' + val.name + '</option>');
                });
            } else {
                $('#city').empty().append(new Option('Select City','', true, true));
                //$('#city').empty().append('<option value="">Select City</option>');
            }
        }, "json");
    }else
    {
        $('#city').empty().append(new Option('Select City','', true, true));
        //$('#city').empty().append('<option value="">Select City</option>');
    }
}

function getzipcode()
{
    var country = $("#country option:selected").html().trim();
    var region = $("#region option:selected").html().trim();
    var city = $("#city option:selected").html().trim();

    if (country != '' && region != '' && city != '')
    {
        var address = city + ' ' + region + ' ' + country;
        //var address = 'shop no. 90 model town phagwara punjab india';
        $.post("getzipcode", {"address": address}, function (data) {

            var result = data.trim().split(',');

            $('#pincode').val(result[0]);
            $('#lat').val(result[1]);
            $('#lon').val(result[2]);
        });
    }

}

const capitalizeFirstLetter = ([ first, ...rest ], locale = navigator.language) =>
  first.toLocaleUpperCase(locale) + rest.join('');
  
   
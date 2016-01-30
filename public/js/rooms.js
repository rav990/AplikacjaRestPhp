/**
 * Adds employee deletion events to all elements with class deleteEquipment.
 * Elements need attribute rel=[employee id].
 */
$(document).ready(function() {
    /**
     * @param {int} id
     * @returns {undefined}
     */
  
    var delEquipment = function(id) {
        var ok = function(data) {
            if ( data['status'] != "ok" ) { alert("AAA)");
                if ( data.hasOwnProperty('message') )
                  alert("Sorry - the response from the server was: " + data['message']);
                else
                  alert("Sorry - deletion may have failed.");
            }
            else {
                $('.equipmentPartial[rel='+id+']').fadeOut();
            }
        }
        $.post("/delete_equipment", {'id': id}, ok)
        .fail(function() {
            alert("Sorry - the request did not work.");
        });
        /*$.ajax({
            type: "POST",
            url: "http://localhost/zend/RestApplication/public/delete_employee",
            data: {'id': id},
            success: function(msg){
                  alert( "Data Saved: " + msg );
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
               alert("some error");
            }
          });*/
    }
    
    
    $('.deleteEquipment').click(function() {
      delEquipment($(this).attr('rel'));
    });
});
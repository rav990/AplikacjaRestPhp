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
    }
    
    
    $('.deleteEquipment').click(function() {
      delEquipment($(this).attr('rel'));
    });
});
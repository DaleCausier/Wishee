jQuery(document).ready(function(){
    
    var UserGiftListDiv     = jQuery('#user-gift-list');
    
    jQuery(document).on('click', '.delete-gift-btn', function(e) {
        var $msg = "Are you sure you want to delete this item?";
        if (confirm($msg)) {
            e.preventDefault();
            var $giftID = jQuery(e.target).attr("id");
            jQuery('.loader').addClass('visible');
            deleteGiftViaAJAX($giftID);
        }
    });
    
    function deleteGiftViaAJAX($giftID) {
        jQuery.ajax({
            method: 'POST',
            url: 'index.php?option=com_wishee&view=list&task=deleteGiftAJAX&format=raw',
            data: {
                gift_id: $giftID
            }
        })
        .done(function($gifts){
            if ( undefined === $gifts || 0 == $gifts.length ) {
                UserGiftListDiv.html(
                    '<p id="default-msg">Your list is currently empty.</p>' +
                    '<i class="fa fa-circle-o-notch loader"></i>'
                );
                jQuery('.loader').removeClass('visible');
            } else {
                UserGiftListDiv.html("");
                jQuery.each($gifts, function(){
                    console.log(this.product_image_url);
                    UserGiftListDiv.append(
                    '<div class="row" style="margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">' +

                        '<div class="col-sm-2">' +
                            '<img ' +
                                'style="max-height: 200px; width: auto;"' +
                                'src="' + this.product_image_url + '"' +
                                'alt="' + this.product_name + '"' +
                            '/>' +
                        '</div>' +

                        '<div class="col-sm-4">' +
                            this.product_name +
                            '<p><a style="margin-top: 10px;" class="btn btn-default" href="' + this.product_store_url + '" target="_blank">' +
                                'Buy on Amazon' +
                            '</a></p>' +
                        '</div>' +

                        '<div class="col-sm-1">' +
                            this.product_category +
                        '</div>' +

                        '<div class="col-sm-1" style="text-align: right;">'+
                            '&pound;' + this.product_price.slice(0, -2) + '.' + this.product_price.slice(-2) +
                        '</div>' +

                        '<div class="col-sm-3" style="text-align: right;">' +
                            '<button type="button" class="btn btn-danger delete-gift-btn" id="' + this.gift_id + '">' +
                                '<i class="fa fa-times"></i> Delete' +
                            '</button>' +
                        '</div>' +

                    '</div>'
                ); 
                });
            }
        });
    }
    
});
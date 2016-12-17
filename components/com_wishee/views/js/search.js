jQuery(document).ready(function(){
   
    /* 
     * Cache DOM nodes to increase script performance.
     *
     * Read more here: https://ttmm.io/tech/selector-caching-jquery/
     */
    var SearchBtn           = jQuery('#search-products-button');
    var ClearBtn            = jQuery('#clear-search-button');
    var SearchResultsDiv    = jQuery('#search-results');
    
    
    /*
     * Search form 'submit' event listener
     *
     * 'e' contains the DOM Event Object; read more on this here: http://javascript.info/tutorial/obtaining-event-object.
     */
    jQuery('#search-form').submit(function(e) {
        
        /* Prevent default DOM Event Object 'submit' behaviour; i.e. prevent the form subit event from reloading the entire page */
        e.preventDefault();
        
        /* Hide the default message that advises users to use the search box to find gits */
        jQuery('#default-msg').hide();
        
        /* Show the loading 'spinner' as feedback to the user that the processing of their request is in operation */
        jQuery('.loader').addClass('visible');
        
        /*
         * Get values from Search Bar and Category select list.
         *
         * These values are passed as data to /components/com_wishee/views/search/view.raw.php
         * via the runAJAXRequest() function.
         */
        var SearchQuery = jQuery('#search_query').val();
        var Category    = jQuery('#category').val();
        
        /* Run the AJAX search request function, passing to it the form values extracted from the DOM */
        runAJAXRequest(SearchQuery, Category);
    });
    
    
    /* Form clear button 'click' event listener */
    ClearBtn.click(function(e) {
        /* Prevent the link click event from redirecting the page using the 'href' value. (Stops '#' being appended to URL) */
        e.preventDefault();
        
        /* Empty the search bar */
        jQuery('#search_query').val("");
        
        /* Remove serach results from page and restore UX message and spinning loader */
        SearchResultsDiv.html(
            '<p id="default-msg">Use the search box above to look for gifts!</p>' +
            '<i class="fa fa-circle-o-notch loader"></i>'
        );
        
        /* Display the default message and hide the spinner */
        jQuery('#default-msg').show();
        jQuery('.loader').removeClass('visible');
    });


    /*jQuery(document).on('click', '.add-gift-btn', function(e) {
        e.preventDefault();
        var el = jQuery(this);
        var product = el.parents('.product');
        var giftData = {
            store_id: 1,
            product_id: product.attr('id'),
            product_name: product.find('.product-name a').html(),
            product_price: product.find('.product-price').html(),
            product_category: product.find('.product-category').html(),
            product_image_url: product.find('img').attr('src'),
            product_store_url: product.find('.product-name a').attr('href'),
            purchased: 0
        };
        addGiftToList(giftData);
    });*/
    
    
    /* Function to execute the AJAX request that adds selected gift to user's list before redirecting to user's list view */
    /*function addGiftToList(GiftData) {
        console.log(GiftData);
        jQuery.ajax({
            method: 'POST',
            url: 'index.php?option=com_wishee&task=addGift',
            data: {
                gift_data: GiftData
            }
        });
    }*/


    /* Function to execute the AJAX request that updates the view with products based on the latest search query */
    function runAJAXRequest(SearchQuery, Category) {
        
        /* For more information, see: http://api.jquery.com/jquery.ajax/ */
        jQuery.ajax({
            
            /* Define the HTTP method to use for the request */
            method: 'POST',
            
            /*
             * @param   'url'   String containing the URL to which the request is sent.
             *
             * At the end of the URL, 'task=ControllerMethod&format=ViewType
             *
             * The data is sent to the displaySearchResults() method in controllers/search.php
             * which in turn loads the view.raw.php file (view.raw.php not view.html.php because of the format=raw in url).
             *
             * The view.raw.php file fires off a call to the getProducts() method in models/search.php, with the $formData
             * passed to this method coming from the 'data' passed to the view via the AJAX request.
             *
             * Long story, short:
             * > User clicks search button
             * > search.js script gets search values and triggers AJAX function that sends search values as data to view.raw.php
             * > view.raw.php gets the data sent to it using Joomla's JInput and stores it in an array called $formData
             * > $formData is then sent to and used by the model to search for products based on the user's search values
             * > view.raw.php then json_encodes the data so that it can be used in the jQuery.ajax().done() function
             *
             * JInput Documentation: https://docs.joomla.org/Retrieving_request_data_using_JInput
             */
            url: 'index.php?option=com_wishee&view=search&format=raw',
            data: {
                search_query: SearchQuery,
                category: Category
            }
        })
        .done(function(Products) {
            SearchResultsDiv.html("");
            console.log(Products);
            populateResults(Products);
        });
    }
    
    function populateResults(Products) {
        
        var array_end = Products.length - 1;
        var count = 0;
        var resultsHTML = '';

        jQuery.each(Products, function(i, Product){
            
            if (count === 0) { resultsHTML += '<div class="row">'; }

            resultsHTML +=
                '<div class="col-sm-4">' +
                    '<div class="product" id="'+Product.product_id+'" itemscope itemtype="https://schema.org/Product">' +
                        '<img class="img-responsive center-block" src="'+ Product.product_images.large_image +'" alt="Product Image" itemprop="image" />' +
                        '<p class="product-name" itemprop="name"><a href="'+ Product.product_store_url +'" title="'+Product.product_name+'">' + Product.product_name.substring(150, 0) + '</a></p>' +
                        '<div class="product-details">' +
                            '<p><span class="small">Product Type: &nbsp;</span><span class="product-category" itemprop="category">'+ Product.product_category +'</span></p>' +
                            '<p class="rrp-price">'+ Product.product_prices.rrp_formatted +'</p>' +
                            '<p class="product-price">'+ Product.product_prices.price_formatted +'</p>' +
                            '<div class="product-buttons">' +
                                '<div class="col-xs-6 product-button add-gift-btn">' +
                                    '<a href="/index.php?option=com_wishee&task=addGift&product_id='+Product.product_id+'"><i class="fa fa-floppy-o"></i> Save to My List</a>' +
                                '</div>' +
                                '<div class="col-xs-6 product-button">' +
                                    '<a href="'+Product.product_store_url+'" target="_blank"><i class="fa fa-shopping-cart"></i> Buy Now</a>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            
            ++count;
            
            if (count === 3) { resultsHTML += '</div>'; count = 0; }

        });

        if (count > 0) { resultsHTML += '</div>'; }

        SearchResultsDiv.html(resultsHTML);

    }
    
});
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
            url: 'index.php?option=com_wishee&view=search&task=displaySearchResults&format=raw',
            data: {
                search_query: SearchQuery,
                category: Category
            }
        })
        .done(function(QueryResults) {
            SearchResultsDiv.html("");
            console.log(QueryResults);
            if ("0" != QueryResults.TotalResults) {
                if ($.isArray(QueryResults.Item)) /* if multiple results */ {
                    var ProductsArray = QueryResults.Item;
                    jQuery.each(ProductsArray, function() {
                        populateResults(this); 
                    });    
                } else /* if only 1 result */ {
                    populateResults(QueryResults.Item);
                }
            } else /* if no results */ {
                SearchResultsDiv.html('Sorry, no results found for, "' + SearchQuery + '" in, "' + Category + '".');
            }
            
        });
    }
    
    function populateResults(Product) {
        var Attrbs = Product.ItemAttributes;
        var Amount = Attrbs.ListPrice ? Attrbs.ListPrice.Amount : false;
        var Price = Amount ? '&pound' + Amount.slice(0, -2) + '.' + Amount.slice(-2) : 'Digital Product';
        var ProductGroup = Attrbs.ProductGroup == "DVD" ? "DVD / BluRay" : Attrbs.ProductGroup;
        var ImgURL;

        if (Product.ImageSets) {
            if ($.isArray(Product.ImageSets.ImageSet)) {
                var ImgArray = Product.ImageSets.ImageSet;
                var ImgData = ImgArray[ImgArray.length-1];
                ImgURL = ImgData.LargeImage.URL;
            } else {
                ImgURL = Product.ImageSets.ImageSet.LargeImage.URL;
            }   
        } else {
            ImgURL = 'http://lorempixel.com/200/200/nature';
        }

        SearchResultsDiv.append(
            '<div class="row" style="margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">' +

                /* Product Image */
                '<div class="col-sm-2">' +
                    '<img style="max-height: 200px; width: auto;" src="' + ImgURL + '" alt="' + Attrbs.Title + '" />' +
                '</div>' +

                /* Product Title */
                '<div class="col-sm-4">' +
                    Attrbs.Title +
                '</div>' +

                /* Product Category */
                '<div class="col-sm-1">' +
                    ProductGroup +
                '</div>' +

                /* Product Price */
                '<div class="col-sm-1" style="text-align: right;">' +
                    Price +
                '</div>' +

                /* Buttons */
                '<div class="col-sm-3" style="text-align: right;">' +
                    '<a href="' + Product.DetailPageURL + '" class="btn btn-default" target="_blank">Buy on Amazon</a>' +
                    '<a href="#" class="btn btn-primary" id="' + Product.ASIN + '"><i class="fa fa-gift"></i> Add to List</a>' +
                '</div>' +

            '</div>'
        );
    }
    
});
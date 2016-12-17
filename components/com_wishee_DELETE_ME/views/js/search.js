jQuery(document).ready(function(){
    
    var SearchBtn           = jQuery('#search-products-button');
    var ClearBtn            = jQuery('#clear-search-button');
    var SearchResultsDiv    = jQuery('#search-results');
    var CategoriesBtn       = jQuery('p#category-toggle');
    var CategoriesToggles   = jQuery('fieldset#categories');
    
    CategoriesBtn.click(function(){
        if (CategoriesToggles.hasClass('open')) {
            CategoriesToggles.stop().slideUp(500);
            jQuery(this).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        } else {
            CategoriesToggles.stop().slideDown(500);
            jQuery(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
        CategoriesToggles.toggleClass('open');
    });
    
    jQuery('#search-form').submit(function(e) {
        e.preventDefault();
        jQuery('#default-msg').hide();
        jQuery('.loader').addClass('visible');
        var SearchQuery = jQuery('#search_query').val();
        runAJAXRequest(SearchQuery);
    });
    
    ClearBtn.click(function(e) {
        e.preventDefault();
        jQuery('#search_query').val("");
        SearchResultsDiv.html(
            '<p id="default-msg">Use the search box above to look for gifts!</p>' +
            '<i class="fa fa-circle-o-notch loader"></i>'
        );
        jQuery('#default-msg').show();
        jQuery('.loader').removeClass('visible');
    });
    
    function runAJAXRequest(SearchQuery) {
        jQuery.ajax({
            type: 'POST',
            url: 'index.php?option=com_wishee&view=search&task=displaySearchResults&format=raw',
            data: {
                search_query: SearchQuery
            }
        })
        .done(function(QueryResults) {
            SearchResultsDiv.html("");
            console.log(QueryResults);
            var ProductsArray = QueryResults.Item;
            console.log(ProductsArray);
            if (ProductsArray.length) {
                jQuery.each(ProductsArray, function() {
                    var Attrbs = this.ItemAttributes;
                    var Amount = Attrbs.ListPrice ? Attrbs.ListPrice.Amount : false;
                    var Price = Amount ? '&pound' + Amount.slice(0, -2) + '.' + Amount.slice(-2) : 'Digital Product';
                    var ImgURL;
                    
                    if (this.ImageSets) {
                        if ($.isArray(this.ImageSets.ImageSet)) {
                            var ImgArray = this.ImageSets.ImageSet;
                            var ImgData = ImgArray[ImgArray.length-1];
                            ImgURL = ImgData.LargeImage.URL;
                        } else {
                            ImgURL = this.ImageSets.ImageSet.LargeImage.URL;
                        }   
                    } else {
                        ImgURL = 'http://lorempixel.com/200/200/nature';
                    }
                    
                    SearchResultsDiv.append(
                        '<div class="row" style="margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">' +
                            
                            
                            '<div class="small-2 columns">' +
                                '<img style="max-height: 200px; width: auto;" src="' + ImgURL + '" alt="' + Attrbs.Title + '" />' +
                            '</div>' +
                        
                            
                            '<div class="small-4 columns">' +
                                Attrbs.Title +
                            '</div>' +
                        
                            
                            '<div class="small-1 columns">' +
                                Attrbs.ProductGroup +
                            '</div>' +
                        
                            
                            '<div class="small-1 columns" style="text-align: right;">' +
                                Price +
                            '</div>' +
                        
                            
                            '<div class="small-3 columns" style="text-align: right;">' +
                                '<a href="' + this.DetailPageURL + '" class="button hollow" target="_blank">Buy on Amazon</a>' +
                                '<a href="#" class="button">&#43 Add to List</a>' +
                            '</div>' +
                            
                        '</div>'
                    ); 
                });
            } else {
                SearchResultsDiv.html("Sorry, no results found for, '" + SearchQuery + "'.");
            }
            
        });
    }
     
});
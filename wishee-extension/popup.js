$(document).ready(function () {
   runAJAXRequest();               
});

function runAJAXRequest() {
    SearchResultsDiv = jQuery('#productresults');
    
    jQuery.ajax({
        //posting data to some PHP script.
        method: 'POST',
        //this is the URL of the PHP function which will recieve the data and return a list of products.
        url : 'http://ct6008-buizkits.studentsites.glos.ac.uk/index.php?option=com_wishee&view=search&task=displaySearchResults&format=raw',
        //data will eventually be grabbed from page's relevant divs instead of hard coded values.
        data: {
            search_query: "trousers",
            category: "All"
        }
    })
    .done(function(QueryResults) {
        console.log(QueryResults);
        //clears div that holds the results
        SearchResultsDiv.html("");
        //checking to see if the results isnt Null
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
            '<div class="small-2 columns">' +
                '<img style="max-height: 200px; width: auto;" src="' + ImgURL + '" alt="' + Attrbs.Title + '" />' +
            '</div>' +

            /* Product Title */
            '<div class="small-4 columns">' +
                Attrbs.Title +
            '</div>' +

            /* Product Category */
            '<div class="small-1 columns">' +
                ProductGroup +
            '</div>' +

            /* Product Price */
            '<div class="small-1 columns" style="text-align: right;">' +
                Price +
            '</div>' +

            /* Buttons */
            '<div class="small-3 columns" style="text-align: right;">' +
                '<a href="' + Product.DetailPageURL + '" class="button hollow" target="_blank">Buy on Amazon</a>' +
                '<a href="#" class="button">&#43 Add to List</a>' +
            '</div>' +

        '</div>'
    );
}
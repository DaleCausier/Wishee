
chrome.runtime.onMessage.addListener(function(request, sender) {
  if (request.action == "getSource") {
    //callback function this stores the background page html within the message div.
    message.innerHTML = request.source;
    //calling the getIDs function once the message div has been filled with content.
    getIds();
  }
});

//function that is called when page is ready.
function onWindowLoad() {

  //selecting the message div, this will be used to store the content of the background page in order to access the DOM elements.
  var message = document.querySelector('#message');

  //executing the getpageinfo.js on the background page.
  chrome.tabs.executeScript(null, {
    file: "getpageinfo.js"
  }, function() {
    // If you try and inject into an extensions page or the webstore/NTP you'll get an error
    if (chrome.runtime.lastError) {
      message.innerText = 'There was an error injecting script : \n' + chrome.runtime.lastError.message;
    }
  });

}

//calling onwindowload function when page is ready.
window.onload = onWindowLoad;
      
//this function gets all the span and Div id's off the page and stores them into an array.
function getIds() {
    console.log("inside getIds function");
	var spanIDs = $("span[id]")
	// find spans with ID attribute
	  .map(function() { return this.id; }) // convert to set of IDs
	  .get(); // convert to instance of Array (optional)
	var divIDs = $("div[id]")  
	        // find Divs with ID attribute
	  .map(function() { return this.id; }) // convert to set of IDs
	  .get(); // convert to instance of Array (optional)

	//combining the two arrays together to form a single array
	var IDs = spanIDs.concat(divIDs);

	//sending the array through to a javascript function
	retrieveInformation(IDs);
}

//this function loops through each of the id's on the page and determines whether our key words are included in the id name.
//it then populate a field depending on whether the keyword is in the id tag.
function retrieveInformation(IDs){
    $('productresults').hide();
    console.log("inside retrieve information");
    var issearch = $('#wisheeinputform').css('display');
    console.log(issearch);
    var namecount =0;
    var pricecount =0;
    var imagecount =0;
    var elsecount =0;
    
    console.log(IDs);
	//looping through each if the IDs
	for (var id of IDs)
	{
        //if the input form is hidden only retrieve the values for the product name
        //then send this name to the amazon search API and retrieve product information.
        if(issearch == "none"){
            
            //checking to to see if the id name has the string included
            if(((id.indexOf("Name") > 0 || id == "Name") || (id.indexOf("name") > 0 || id == "name") || (id.indexOf("title") > 0 || id == "title") || (id.indexOf("Title") > 0 || id == "Title")) && namecount < 1)
            {
                console.log("inside big if statement" + " " + id);
                namecount ++;
                var content = document.getElementById(id).innerHTML;
                //sending the inner html of the ID which matched the keywords along with the "All" category.
                runAJAXRequest(content, "All");
            }
            else if(((id.indexOf("Name") == -1 || id != "Name") || (id.indexOf("name") == -1 || id != "name") || (id.indexOf("title") == -1 || id != "title") || (id.indexOf("Title") == -1 || id != "Title")) && namecount < 1 && elsecount < 1)
            {
                elsecount ++;
                console.log("got in else statement");
                $('#productresults').append("sorry there are no results");
            }
            
        }
        else{
            
            //checking to to see if the id name has the string included
            if(((id.indexOf("Name") > 0 || id == "Name") || (id.indexOf("name") > 0 || id == "name") || (id.indexOf("title") > 0 || id == "title") || (id.indexOf("Title") > 0 || id == "Title")) && namecount < 1)
            {
                namecount++;
                var content = document.getElementById(id).innerHTML;
                //title input box
                document.getElementById("field1").value = content;
            }
            else if(((id.indexOf("Price") > 0 || id == "Price") || (id.indexOf("price") > 0 || id == "price")) && pricecount < 1)
            {
                pricecount ++;
                var content = document.getElementById(id).innerHTML;
                //price input box
                document.getElementById("field2").value = content;
            }
            else if(((id.indexOf("Image") > 0 || id == "Image") || (id.indexOf("image") > 0 || id == "image")) && pricecount < 1)
            {
                imagecount ++;
                var content = document.getElementById(id).innerHTML;
                //image div
                document.getElementById("landing").innerHTML = content;
            }

        }
        

	}
    $('#productresults').show();
}

function runAJAXRequest(searchQuery, Category) {
    console.log("inside run ajax function" + " " + searchQuery + " " + Category);
    SearchResultsDiv = jQuery('#productresults');
    
    jQuery.ajax({
        //posting data to some PHP script.
        method: 'POST',
        //this is the URL of the PHP function which will recieve the data and return a list of products.
        url : 'http://ct6008-buizkits.studentsites.glos.ac.uk/index.php?option=com_wishee&view=search&task=displaySearchResults&format=raw',
        //data will eventually be grabbed from page's relevant divs instead of hard coded values.
        data: {
            search_query: searchQuery,
            category: Category
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
        '<div style="margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">' +

            /* Product Image */
            '<div>' +
                '<img style="max-height: 200px; width: auto;" src="' + ImgURL + '" alt="' + Attrbs.Title + '" />' +
            '</div>' +

            /* Product Title */
            '<div>' +
                Attrbs.Title +
            '</div>' +

            /* Product Category */
            '<div>' +
                ProductGroup +
            '</div>' +

            /* Product Price */
            '<div style="text-align: right;">' +
                Price +
            '</div>' +

            /* Buttons */
            '<div style="text-align: right;">' +
                '<a href="' + Product.DetailPageURL + '" target="_blank">Buy on Amazon</a>' +
                '<a href="#">&#43 Add to List</a>' +
            '</div>' +

        '</div>'
    );
}
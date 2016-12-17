<table>
    
    <!-- Headings -->
    <tr>
        <th>Image</th>
        <th>Product Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Add to List</th>
        <th>Buy It</th>
    </tr>
    
    <!-- Results -->
    <?php foreach($this->results->Item as $result) { 
        $attrbs = $result->ItemAttributes;
        $amount = $attrbs->ListPrice ? $attrbs->ListPrice->Amount : false;
        $price = $amount ? '&pound;'.substr($amount, 0, -2).'.'.substr($amount, 2) : 'Digital Product'; ?>
    <tr>
        <td><img src="<?php echo $result->MediumImage->URL; ?>" alt="<?php echo $attrbs->Title; ?>"/></td>
        <td><?php echo $attrbs->Title; ?></td>
        <td><?php echo $attrbs->ProductGroup; ?></td>
        <td><?php echo $price; ?></td>
        <td><a href="#">&#43; Add to List</a></td>
        <td><a href="<?php echo $result->DetailPageURL; ?>" target="_blank">Buy Gift</a></td>
    </tr>
    <?php } ?>
</table>
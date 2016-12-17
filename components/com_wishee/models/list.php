<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Wishee Search Model
 *
 * @since  0.0.1
 */
class WisheeModelList extends JModelList {
    
    private $amazonAccessKey = 'AKIAI4Q3F4IO4CJZGDBQ';
    private $amazonSecretKey = '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';
    private $amazonAssociateTag = 'wishee0a-21';

    public function getGiftList() {
        
        $userID = JFactory::getUser()->id;
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('*')
              ->from($db->quoteName('#__wishee_gifts', 'gifts'))
              ->where($db->quoteName('gifts.user_id') . ' = ' . $userID);
        
        $db->setQuery($query);
        
        $gifts = $db->loadObjectList();
        
        return $gifts;
        
    }
    
    public function deleteGift($giftID) {
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->delete($db->quoteName('#__wishee_gifts'))
              ->where($db->quoteName('#__wishee_gifts.gift_id') . ' = ' . $giftID);
        
        $db->setQuery($query);
        $db->execute();
        
        $gifts = $this->getGiftList();
        
        return $gifts;
        
    }

    public function addGift($productID) {

        $product_data = array();
        $item = $this->itemLookup($productID);

        $product_data['user_id'] = JFactory::getUser()->id;
        $product_data['store_id'] = 1;
        $product_data['product_id'] = $productID;
        $product_data['product_name'] = $item->ItemAttributes->Title;

        // Set Price
        if(isset($item->Offers->Offer)) {
            $product_data['product_price'] = $item->Offers->Offer->OfferListing->Price->FormattedPrice;
        } elseif($item->OfferSummary->TotalNew > 0) {
            $product_data['product_price'] = $item->OfferSummary->LowestNewPrice->FormattedPrice;
        } else {
            $product_data['product_price'] = 'No Price';
        }

        $product_data['product_category'] = $item->ItemAttributes->ProductGroup == "DVD" ? "DVD / BluRay" : $item->ItemAttributes->ProductGroup;;
        
        // Set Image
        if(isset($item->ImageSets->ImageSet)) {
            if(is_array($item->ImageSets->ImageSet)) {
                $imagesArray = end($item->ImageSets->ImageSet);
                $product_data['product_image_url'] = $imagesArray->LargeImage->URL;
            } else {
                $product_data['product_image_url'] = $item->ImageSets->ImageSet->LargeImage->URL;
            }
        } else {
            $product_data['product_image_url'] = 'http://icongal.com/gallery/image/32068/gift_present.png';
        }

        $product_data['product_store_url'] = $item->DetailPageURL;
        $product_data['purchased'] = 0;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $sqlValues = array(
            (int)$product_data['user_id'],
            (int)$product_data['store_id'],
            $db->quote($product_data['product_id']),
            $db->quote($product_data['product_name']),
            $db->quote($product_data['product_price']),
            $db->quote($product_data['product_category']),
            $db->quote($product_data['product_image_url']),
            $db->quote($product_data['product_store_url']),
            (int)$product_data['purchased']
        );

        $query->insert($db->quoteName('#__wishee_gifts'))
              ->columns($db->quoteName(array(
                  'user_id',
                  'store_id',
                  'product_id',
                  'product_name',
                  'product_price',
                  'product_category',
                  'product_image_url',
                  'product_store_url',
                  'purchased'
                )))
              ->values(implode(',', $sqlValues));
        
        $db->setQuery($query);
        
        try {
            $success = $db->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $success;

    }

    private function itemLookup($productID) {
        // The region you are interested in
        $endpoint = "webservices.amazon.co.uk";
        $uri = "/onca/xml";

        $urlRequestParams = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemLookup",
            "AWSAccessKeyId" => $this->amazonAccessKey,
            "AssociateTag" => $this->amazonAssociateTag,
            "ItemId" => $productID,
            "IdType" => "ASIN",
            "ResponseGroup" => "Images,Medium,OfferFull"
        );

        // Set current timestamp if not set
        if (!isset($urlRequestParams["Timestamp"])) {
            $urlRequestParams["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        // Sort the parameters by key
        ksort($urlRequestParams);

        $pairs = array();

        foreach ($urlRequestParams as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        // Generate the canonical query
        $canonical_query_string = join("&", $pairs);

        // Generate the string to be signed
        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

        // Generate the signature required by the Product Advertising API
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $this->amazonSecretKey, true));

        // Generate the signed URL
        $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

        
        $response = file_get_contents($request_url);
        $parsed_xml = simplexml_load_string($response);
        $product_data = json_decode(json_encode(simplexml_load_string($response)));
        
        return $product_data->Items->Item;
    }
    
}
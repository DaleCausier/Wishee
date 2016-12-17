<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE.'/components/com_wishee/vendor/autoload.php';

use ApaiIO\ApaiIO;
use ApaiIO\Configuration\GenericConfiguration;

/**
 * Wishee Search Model
 *
 * @since  0.0.1
 */
class WisheeModelSearch extends JModelForm {
    
    protected $products;
    private $amazonAccessKey = 'AKIAI4Q3F4IO4CJZGDBQ';
    private $amazonSecretKey = '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';
    private $amazonAssociateTag = 'wishee0a-21';

    public function getForm($data = array(), $loadData = true) {
        // Get the Search form
        $form = $this->loadForm('com_wishee.search', 'search', array('load_data' => $loadData));
        
        if (empty($form)) {
            return false;
        }
        
        return $form;
    }
    
    public function getProducts($formData) {

        $conf = new GenericConfiguration();
        $client = new \GuzzleHttp\Client();
        $request = new \ApaiIO\Request\GuzzleRequest($client);

        $reultsPages = 2; // Each results page contains 10 items

        try {
            $conf
                ->setCountry('co.uk')
                ->setAccessKey($this->amazonAccessKey)
                ->setSecretKey($this->amazonSecretKey)
                ->setAssociateTag($this->amazonAssociateTag)
                ->setRequest($request);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $apaiIO = new ApaiIO($conf);

        for($i=1; $i <= $reultsPages; $i++) {
            ${'search'.$i} = new \ApaiIO\Operations\Search();
            ${'search'.$i}->setCategory($formData['category']);
            ${'search'.$i}->setKeywords($formData['search_query']);
            ${'search'.$i}->setResponsegroup(array('Medium', 'Images', 'OfferFull'));
            ${'search'.$i}->setMerchantId('All');
            ${'search'.$i}->setPage($i);
        }

        $batch = new \ApaiIO\Operations\Batch();
        
        for($i=1; $i <= $reultsPages; $i++) {
            $batch->addOperation(${'search'.$i});
        }

        $xmlResponse = $apaiIO->runOperation($batch);
        $allResults = json_decode(json_encode(simplexml_load_string($xmlResponse)));
        //return $allResults->Items; die();

        $products = $this->createProductsArray($allResults->Items);
        return $products;
        
    }

    private function createProductsArray($resultPages) {
        
        // Products array to contain custom product data arrays
        $products = array();

        foreach($resultPages as $resultPage) {
            $items = $resultPage->Item;
            foreach($items as $k => $item) {
                
                // Set Price
                $prices = array();
                if(isset($item->Offers->Offer)) {
                    $prices['price'] = $item->Offers->Offer->OfferListing->Price->Amount;
                    $prices['price_formatted'] = $item->Offers->Offer->OfferListing->Price->FormattedPrice;
                } elseif($item->OfferSummary->TotalNew > 0) {
                    $prices['price'] = $item->OfferSummary->LowestNewPrice->Amount;
                    $prices['price_formatted'] = $item->OfferSummary->LowestNewPrice->FormattedPrice;
                }
                
                // Set RRP
                if(isset($item->ItemAttributes->ListPrice)) {
                    if ($prices['price'] === $item->ItemAttributes->ListPrice->Amount ){
                        $prices['rrp'] = '';
                        $prices['rrp_formatted'] = '';
                    } else {
                        $prices['rrp'] = $item->ItemAttributes->ListPrice->Amount;
                        $prices['rrp_formatted'] = $item->ItemAttributes->ListPrice->FormattedPrice;
                    }
                } else {
                    $prices['rrp'] = '';
                    $prices['rrp_formatted'] = '';
                }

                // Set Images
                $images = array();
                if(isset($item->ImageSets->ImageSet)) {
                    if(is_array($item->ImageSets->ImageSet)) {
                        $imagesArray = end($item->ImageSets->ImageSet);
                        $images['large_image'] = $imagesArray->LargeImage->URL;
                        $images['hi_res_image'] = isset($imagesArray->HiResImage) ? $imagesArray->HiResImage->URL : $imagesArray->LargeImage->URL;
                    } else {
                        $images['large_image'] = $item->ImageSets->ImageSet->LargeImage->URL;
                        $images['hi_res_image'] = isset($item->ImageSets->ImageSet->HiResImage) ? $item->ImageSets->ImageSet->HiResImage->URL : $item->ImageSets->ImageSet->LargeImage->URL;
                    }
                } else {
                    $images['large_image'] = 'http://icongal.com/gallery/image/32068/gift_present.png';
                    $images['hi_res_image'] = 'http://icongal.com/gallery/image/32068/gift_present.png';
                }

                // Create array of product data with Amazon data
                $product = array();
                $product['store_id'] = 1;
                $product['product_id'] = $item->ASIN;
                $product['product_name'] = $item->ItemAttributes->Title;
                $product['product_prices'] = $prices;
                $product['product_category'] = $item->ItemAttributes->ProductGroup == "DVD" ? "DVD / BluRay" : $item->ItemAttributes->ProductGroup;
                $product['product_images'] = $images;
                $product['product_store_url'] = $item->DetailPageURL;
                
                // Push product into custom Products array
                array_push($products, $product);
            }
        }
        
        return $products;
    }
    
}
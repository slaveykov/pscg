<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once ("interfaces/IStoreHandler.php");

class Wayfair_handler implements IStoreHandler {

	private $error = "";
	private $store = "wayfair";
	private $imgHost = "https://secure.img2.wfrcdn.com";

	public function __construct($params) {
		
		if (isset($params['store'])) {
			if(isset($params['store']['internal_name'])){
				$this->store = $params['store']['internal_name'];
			}
		}
		$this->CI = & get_instance();
		$this->CI->load->model("Proxy_model", "proxy", TRUE);
		$this->CI->load->helper("ProxyCURL");
		
		libxml_use_internal_errors(true);
	}

	public function setStore($storeData) {
		$this->store = $storeData['internal_name'];
	}

	public function getProduct($id) {
		
		$this->error = "";
		
		if ($this->store == "wayfair_co_uk") {
			$link = "http://www.wayfair.co.uk/-$id.html";
		} else {
			$link = "http://www.wayfair.com/-$id.html";
		}
		
		$proxyData = $this->CI->proxy->getData();
		if(empty($proxyData)){
			$this->error .= "Cant get proxy list.";
		}
		$backData = getDataProxyCURL($proxyData['ip'], $proxyData['port'], $proxyData['username'], $proxyData['password'], $link);
		
		if ($backData['error'] == true) {
			$this->error .= "Proxy error:".$backData['error'];
			$this->CI->proxy->markErrored($proxyData['id']);
		} else {
			$content = $backData['result'];
		}
		$price = '0.00';
		$stock_count = null;
		$dataArray = array();
		$extra = array();
		
		if (! empty($content)) {
			$doc = new DOMDocument();
			$doc->validateOnParse = true;
			$doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">' . $content);
			$metas = $doc->getElementsByTagName('meta');
			for($i = 0; $i < $metas->length; $i++) {
				$meta = $metas->item($i);
				if ($meta->getAttribute('property') == 'og:title') $title = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:image') $image = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:description') $description = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:price:currency') $currency = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:upc') $upc = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:url') $url = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:price:amount') $price = $meta->getAttribute('content');
			}
			
			$spans = $doc->getElementsByTagName('span');
			for($i = 0; $i < $spans->length; $i++) {
				$span = $spans->item($i);
				if ($span->getAttribute('data-id') == 'dynamic-sku-price')  $price = $span->nodeValue; $price = str_replace("$","",$price);
			}
			
			$finder = new DomXPath($doc);
			$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' stock_count ')]");
			foreach ($nodes as $node) {
				$stock_count = trim($node->nodeValue);
			}
			
			if ($stock_count == "Out of Stock") {
				$stock = 0;
			} else if ($stock_count == "Stock Status Unknown") {
				$stock = 0;
			} else {
				$stock = 1;
			}
			
			if ($price < '49.99') {
				$extra['standardShipRate'] = '4.99';
			} else {
				$extra['standardShipRate'] = '0.00';
			}
			if (! empty($title)) {
				$dataArray['id'] = $id;
				$dataArray['title'] = $title;
				$dataArray['price'] = $price;
				$dataArray['url'] = $url;
				$dataArray['currency'] = $currency;
				$dataArray['picture'] = $image;
				$dataArray['largeImage'] = $image;
				$dataArray['inStock'] = $stock;
				$dataArray['extra_data'] = $extra;
			} else {
				$this->error .= "Cant get product.";
				$this->CI->proxy->markErrored($proxyData['id']);
				$dataArray = null;
			}
			
			$imagesArray = array();
			$images = $doc->getElementsByTagName('img');
			for($i = 0; $i < $images->length; $i++) {
				$image = $images->item($i);
				if ($image->getAttribute('class') == 'ProductDetailImagesBlock-carousel-image') $imagesArray[] = $image->getAttribute('src');
			}
			//product images
			if (isset($imagesArray) && !empty($imagesArray)) {
				$i_img_g = 0;
				foreach ($imagesArray as $imgGall) {
					$dataArray['more_details']['image_gallery'][$i_img_g]['imageURL'] = $imgGall;
					$i_img_g ++;
				}
			}
			$description = '';
			$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' ProductDetailSpecifications-section ')]");
			$inode=0;
			foreach ($nodes as $node) {
				if($inode==0){
					$description .= trim($node->nodeValue);
				}else{
					$description .= '<br /><br />'.trim($node->nodeValue);
				}
				$inode++;
			}
			if(!empty($description)){
				$dataArray['more_details']['description'] = $description;
			}
			
		} else {
			$this->error .= "Cant load proxy content.";
			$this->CI->proxy->markErrored($proxyData['id']);
			$dataArray = null;
		}
		return $dataArray;
	}

	public function getVariations($link) {
		
		$this->error = "";
		
		$proxyData = $this->CI->proxy->getData();
		
		if(empty($proxyData)){
			$this->error .= "Cant get proxy list.";
		}
		
		if(strlen($link) < 10){
			if ($this->store == "wayfair_co_uk") {
				$link = "http://www.wayfair.co.uk/wsg-$link.html";
			} else {
				$link = "http://www.wayfair.com/wsg-$link.html";
			}
		}
		
		$backData = getDataProxyCURL($proxyData['ip'], $proxyData['port'], $proxyData['username'], $proxyData['password'], $link);
		
		if ($backData['error'] == true) {
			$this->error .= "Proxy error:".$backData['error'];
			$this->CI->proxy->markErrored($proxyData['id']);
		} else {
			$content = $backData['result'];
		}
		
		$dataArray = array();
		
		if (! empty($content)) {
			$doc = new DOMDocument();
			$doc->validateOnParse = true;
			$doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">' . $content);
			$metas = $doc->getElementsByTagName('meta');
			for($i = 0; $i < $metas->length; $i++) {
				$meta = $metas->item($i);
				if ($meta->getAttribute('property') == 'og:title') $title = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:image') $image = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:description') $description = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:price:currency') $currency = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:upc') $upc = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:url') $url = $meta->getAttribute('content');
				if ($meta->getAttribute('property') == 'og:price:amount') $price = $meta->getAttribute('content');
			}
			$spans = $doc->getElementsByTagName('span');
			for($i = 0; $i < $spans->length; $i++) {
				$span = $spans->item($i);
				if ($span->getAttribute('data-id') == 'dynamic-sku-price')  $price = $span->nodeValue; $price = str_replace("$","",$price);
			}
			
			$finder = new DomXPath($doc);
			$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' stock_count ')]");
			foreach ($nodes as $node) {
				$stock_count = trim($node->nodeValue);
			}
			if (isset($title)) {
				$dataArray[0]['productTitle'] = $title;
				$dataArray[0]['productImage'] = $image;
				$dataArray[0]['productDescription'] = $description;
				$dataArray[0]['productPrice'] = $price;
				$dataArray[0]['productCurrency'] = $currency;
				$dataArray[0]['productUrl'] = $url;
				$dataArray[0]['productUpc'] = $upc;
				if (isset($stock_count)) {
					if ($stock_count == "Out of Stock") {
						$dataArray[0]['productStock'] = 0;
					} else if ($stock_count == "Stock Status Unknown") {
						$dataArray[0]['productStock'] = 0;
					} else {
						$dataArray[0]['productStock'] = 1;
					}
				} else {
					$dataArray[0]['productStock'] = 0;
				}
				if ($price < '49.99') {
					$dataArray[0]['productShipping'] = '4.99';
				} else {
					$dataArray[0]['productShipping'] = '0.00';
				}
			}
			
			$imagesArray = array();
			$images = $doc->getElementsByTagName('img');
			for($i = 0; $i < $images->length; $i++) {
				$image = $images->item($i);
				if ($image->getAttribute('class') == 'ProductDetailImagesBlock-carousel-image') $imagesArray[] = $image->getAttribute('src');
			}
			//product images
			if (isset($imagesArray) && !empty($imagesArray)) {
				$i_img_g = 0;
				foreach ($imagesArray as $imgGall) {
					$dataArray[0]['more_details']['image_gallery'][$i_img_g]['imageURL'] = $imgGall;
					$i_img_g ++;
				}
			}
			$description = '';
			$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' ProductDetailSpecifications-section ')]");
			foreach ($nodes as $node) {
				$description .= trim($node->nodeValue);
			}
			
		}
		if(empty($dataArray)){
			//mark ass error
			$this->error .= "Cant load product data.";
			$this->CI->proxy->markErrored($proxyData['id']);
			return NULL;
		}else{
			return $dataArray;
		}
	}

	public function getError() {
		return $this->error;
	}

	public function parseId($idOrURL) {
		
		$last = explode("-", $idOrURL);
		$last = end($last);
		$idOrURL = str_replace(".html","",$last);
		
		return $idOrURL;
	}

}
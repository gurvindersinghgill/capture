<?php
/**
 * PHP script to find content from external page.
 * Find some selected elements by already knowing the xpaths/selector path for those elements
 * capture.php 
 *
 */

//Set UTF-8 as the character set for all headers output by your PHP code
//header('Content-Type: text/html; charset=utf-8');

//page url to catch content
$url = 'http://www.pixmania.co.uk/roadworks/playmobil-4820-ladder-unit/04373247-a.html';

//xpaths of elements that need to find on page
$xpaths = array(
  "Product" => "//*[@id='desc_refresh']/section[1]/h1[1]",
  "Price" => "//*[@id='desc_refresh']/section[1]/div[1]/section[1]/div[1]/div[3]/p[1]/span[1]/ins[1]",
  "In stock" => "//*[@id='desc_refresh']/section[1]/div[1]/section[1]/div[1]/div[2]/div[1]/strong[1]"
);


//call function to get content
$results = getDomContent($url, $xpaths);
foreach($results as $element => $result){
    echo $element.": ".$result."\r\n";
}

/**
 * Find content from third party page with xpath using php DOMDocument
 * @method: getDomContent
 * @return Array
 */
function getDomContent($url, $xpaths){
    // Create a new DOM Document to hold webpage structure 
    $doc = new DOMDocument();
    
    // Load the url's contents into the DOM 
    @$doc->loadHTMLFile($url);
    
    //Create new DOM for xpath
    $selector = new DOMXpath($doc);
    
    //gether output results in array for each element
    $cnt = array();
    
    //go through all xpath
    foreach($xpaths as $key => $xpath){
        $elements = $selector->query($xpath);
        if (!is_null($elements)) {
            foreach ($elements as $element) {
                $item = $element->nodeValue;
                if($key == 'Price')
                {
                    $item = preg_replace("/[^0-9\.]/", '', $item);
                }
                $cnt[$key] = trim($item);
            }
        }
    }
    return $cnt;
}
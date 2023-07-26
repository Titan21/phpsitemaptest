<?php


function generateSitemapIndex(){
    // Create a new XML document
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sitemapindex></sitemapindex>');
    $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

    $sitemapPaths = [];

    for ($i = 0; $i <= 20; $i++) {
        $limit = 100;
        $sitemapPaths[] =  saveGZIP(generateSitemap($limit,$limit * $i), "sitemap_$i.xml");
    }

    foreach ($sitemapPaths as $sitemapPath){
        $loc = $xml->addChild('sitemap');
        $loc->addChild('loc', $sitemapPath);
    }

    $xml->asXML('sitemapindex.xml');

}

function generateSitemap($limit, $offset){
    // Create a new XML document
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
    $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

    // Array containing URLs to be added to the sitemap
    $urls = generateURLs($limit, $offset);

    // Loop through the URLs and add them to the sitemap
    foreach ($urls as $url) {
        $urlNode = $xml->addChild('url');
        $urlNode->addChild('loc', $url);
        $urlNode->addChild('lastmod', getRandomDate());

        // $size_b = strlen($xml->asXML());
        // print_r("Current size: $size_b\n");
        // $urlNode->addChild('priority', '0.8');
    }

    // Save the XML document
    return $xml->asXML();
}

function saveGZIP($xml, $path, $compress = false){

    if($compress){
        $gzippedString = gzencode($xml, 9); // You can adjust the compression level (0-9) as needed
    
        // Save the gzipped string to a file
        $path = "$path.gz";
        file_put_contents($path, $gzippedString);

    }else{
        file_put_contents($path, $xml);
    }

    return $path;
}


function generateURLs($limit = 5, $offset = 0) {
    $urls = [];

    # Simulate DB activity
    // usleep(200000);

    for ($i = $offset + 1; $i <= ($offset + $limit); $i++) {
        $url = "https://longassurl.customdomain.customer.com/nodes/view/$i";
        // usleep(2);
        $urls[] = $url;
    }

    return $urls;
}

function getRandomDate(){
    $startDate = strtotime('-365 days');  // Get the timestamp of 365 days ago
    $endDate = time();  // Get the current timestamp

    $randomTimestamp = mt_rand($startDate, $endDate);  // Generate a random timestamp between start and end date
    $randomDate = date('Y-m-d', $randomTimestamp);  // Format the random timestamp

    return $randomDate;
}

// generateSitemap(5,10);
saveGZIP(generateSitemap(50000,10), "sitemap.xml");
// generateSitemapIndex();

<?php

/*
* @project: TikTok Custom PHP Scraper
*
* @description: I have many TikTok releated web projects and I was looking
* for a TikTok Custom PHP API or Scraper, All what I found was paid,
* limited scripts and most of them are in Python. I do not want so many
* features, I just want a simple script which I give it a TikTok Video
* URL then I want to get few details about it: Canonical URL, Username & Video ID
* That is it! So I decided to write my own custom PHP script to get these details.
* Maybe you guys want the same thing, Enjoy!
*
* Note: This script can be customized to scrape any additional data you want for a TikTok Video
* I have only implemented the features that fits my needs.
*
* @author: Haian K. Ibrahim (GitHub: @hki98)

* @link: https://linkedin.com/in/haian-k-ibrahim
*/

// Autoload
require 'vendor/autoload.php';
// Simple HTML DOM
require 'simple_html_dom.php';

// User GuzzleHTTP Client
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

// First we must set the [Access-Control-Allow-Origin] header,
// so when this script is uploaded on our hosting no hosts can
// send requests and use it except the hosts we already declare.
// YOU CAN SET IT TO ALLOW ALL HOSTS USING [*] BUT THIS IS NOT RECOMMENDED.
header("Access-Control-Allow-Origin: YOUR_DOMAIN_HERE");

// We will get the TikTok video URL using ['url'] GET parameter, if you want
// to use POST parameter then change it.
$url = $_GET['url'];

// Basic URL validation: ISSET && NOT EMPTY && IT IS A TIKTOK URL
if (isset($url) && !empty($url) && str_contains($url, 'tiktok.com')) {
    // Wrap everything in a try/catch to prevent sever 500 error
    // if anything goes wrong.
    try {
        // Initialize GuzzleHTTP client
        $client = new Client();

        // Perform a GET request to the URL using GuzzleHTTP
        $response = $client->get($url);

        // Extract HTML using Simple HTML DOM from response body
        $simpleHTMLDom = str_get_html($response->getBody());

        // TikTok removed og:url and most of OG meta tags and moved to JSON
        // Objects included in the video page at the very bottom.
        // The SCRIPT ID is: [__UNIVERSAL_DATA_FOR_REHYDRATION__]
        // We are searching for this script in the exracted HTML using ID
        $html = $simpleHTMLDom->find('script[id=__UNIVERSAL_DATA_FOR_REHYDRATION__]', 0);

        // Now after we found the script, it needs some cleaning to get
        // only the included JSON without <script> and </script> tags.
        // First, let's remove the <script> tag.
        $script1 = substr($html, 72);

        // Now remove the </script> tag.
        $script2 = explode('</script>', $script1);

        // Now let's decode the extracted JSON from the script
        $decode = json_decode($script2[0], true);

        // Now loop through the JSON content and extract JSON Objects
        foreach ($decode as $object) {
            // Get the JSON object named [seo.abtest] which contains the canonical URL
            // of the given video, then we will extract it and do some clean up.
            $json = json_encode($object['seo.abtest'], true);

            // Decode the contents of [seo.abtest] JSON Object
            $jdec = json_decode($json);

            // Now let's clean the canonical URL. First, explode string at @ mark
            $canonical = explode('/@', $jdec->canonical);

            // Second, explode canonical string at [/video/] and this will
            // give us the username of the video owner at the first index $username[0]
            $username = explode("/video/", $canonical[1]);

            // Third, explode username string at [?] mark to remove unwanted
            // URL analytics parameters and this will give us the ID of the
            // video at the first index $video_id[0]
            $video_id = explode('?', $username[1]);

            // Now everything is done! Return the video details.
            $result = array(
                'status' => 'ok',
                'link' => $jdec->canonical,
                'username' => $username[0],
                'video_id' => $video_id[0],
            );
        }
    } catch (GuzzleException $e) {
        // Return error message with details
        $result = array(
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage(),
        );
    }
} else {
    // Return error message
    $result = array(
        'status' => 'error',
        'message' => 'Please enter a valid TikTok URL!',
    );
}

// Finally, Print the video details.
echo json_encode($result, JSON_PRETTY_PRINT);

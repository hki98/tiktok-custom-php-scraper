<?php
/*
* @project: TikTok PHP Scraper
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

namespace CustomTikTokScraper;

require 'simple_html_dom.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TikTokScraper
{
    private $url;

    /**
     * Constructor to initialize the TikTokScraper with a TikTok video URL.
     *
     * @param string $url TikTok video URL.
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Main function to scrape video details from the TikTok URL.
     *
     * @return array Video details or error message.
     */
    public function scrapeVideoDetails()
    {
        if (!$this->isValidUrl($this->url)) {
            return $this->createErrorResponse('Please enter a valid TikTok URL!', 0);
        }

        try {
            $response = $this->fetchPageContent($this->url);
            $scriptContent = $this->extractScriptContent($response);
            $videoDetails = $this->extractVideoDetailsFromJson($scriptContent);

            if (!$this->isValidVideoDetails($videoDetails)) {
                return $this->createErrorResponse('Please enter a valid TikTok URL!', 0);
            }

            return $this->createSuccessResponse($videoDetails);

        } catch (GuzzleException $e) {
            return $this->createErrorResponse('Something went wrong: ' . $e->getMessage(), 2);
        }
    }

    /**
     * Validates the given URL to ensure it is a TikTok URL.
     *
     * @param string $url The URL to validate.
     * @return bool True if the URL is valid, otherwise false.
     */
    private function isValidUrl($url)
    {
        return strpos($url, 'tiktok.com') !== false && $url !== 'https://www.tiktok.com';
    }

    /**
     * Fetches the page content of the given URL using GuzzleHTTP.
     *
     * @param string $url The URL of the TikTok video.
     * @return string The HTML content of the page.
     */
    private function fetchPageContent($url)
    {
        $client = new Client();
        $response = $client->get($url);
        return str_get_html($response->getBody());
    }

    /**
     * Extracts the content of the specific script tag from the HTML.
     *
     * @param string $html The HTML content of the page.
     * @return string The content of the script tag.
     */
    private function extractScriptContent($html)
    {
        $script = $html->find('script[id=__UNIVERSAL_DATA_FOR_REHYDRATION__]', 0);
        return substr($script, 72, strpos($script, '</script>') - 72);
    }

    /**
     * Extracts video details from the JSON content within the script tag.
     *
     * @param string $scriptContent The content of the script tag.
     * @return array The extracted video details.
     */
    private function extractVideoDetailsFromJson($scriptContent)
    {
        $decodedContent = json_decode($scriptContent, true);

        foreach ($decodedContent as $object) {
            return [
                'canonical' => $object['seo.abtest']['canonical'] ?? '',
                'videoId' => $object['webapp.video-detail']['itemInfo']['itemStruct']['id'] ?? '',
                'description' => $object['webapp.video-detail']['itemInfo']['itemStruct']['desc'] ?? '',
                'user' => $object['webapp.video-detail']['itemInfo']['itemStruct']['author']['nickname'] ?? '',
                'username' => $object['webapp.video-detail']['itemInfo']['itemStruct']['author']['uniqueId'] ?? '',
                'userId' => $object['webapp.video-detail']['itemInfo']['itemStruct']['author']['id'] ?? '',
                'thumbnail' => $object['webapp.video-detail']['itemInfo']['itemStruct']['video']['dynamicCover'] 
                                ?? $object['webapp.video-detail']['itemInfo']['itemStruct']['video']['cover'] ?? '',
                'views' => $object['webapp.video-detail']['itemInfo']['itemStruct']['stats']['playCount'] ?? 0,
                'likes' => $object['webapp.video-detail']['itemInfo']['itemStruct']['stats']['diggCount'] ?? 0,
                'comments' => $object['webapp.video-detail']['itemInfo']['itemStruct']['stats']['commentCount'] ?? 0,
                'shares' => $object['webapp.video-detail']['itemInfo']['itemStruct']['stats']['shareCount'] ?? 0,
                'favorites' => $object['webapp.video-detail']['itemInfo']['itemStruct']['stats']['collectCount'] ?? 0,
            ];
        }

        return [];
    }

    /**
     * Validates the extracted video details.
     *
     * @param array $videoDetails The extracted video details.
     * @return bool True if the video details are valid, otherwise false.
     */
    private function isValidVideoDetails($videoDetails)
    {
        return !empty($videoDetails['videoId']) && !empty($videoDetails['userId']) && !empty($videoDetails['username']);
    }

    /**
     * Creates an error response with the given message.
     *
     * @param string $message The error message.
     * @return array The error response.
     */
    private function createErrorResponse($message, $code)
    {
        return [
            'status' => 'error',
            'code' => $code,
            'message' => $message,
        ];
    }

    /**
     * Creates a success response with the extracted video details.
     *
     * @param array $videoDetails The extracted video details.
     * @return array The success response.
     */
    private function createSuccessResponse($videoDetails)
    {
        return [
            'status' => 'ok',
            'link' => $videoDetails['canonical'],
            'user' => $videoDetails['user'],
            'username' => $videoDetails['username'],
            'user_id' => $videoDetails['userId'],
            'video_id' => $videoDetails['videoId'],
            'video_desc' => $videoDetails['description'],
            'thumbnail' => $videoDetails['thumbnail'],
            'views' => $videoDetails['views'],
            'likes' => $videoDetails['likes'],
            'comments' => $videoDetails['comments'],
            'shares' => $videoDetails['shares'],
            'favorites' => $videoDetails['favorites'],
        ];
    }
}

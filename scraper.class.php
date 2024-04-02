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

namespace CustomTikTokScraper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TikTokScraper
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function scrapeVideoDetails()
    {
        // URL Validation
        if (strpos($this->url, 'tiktok.com') !== false && $this->url !== 'https://www.tiktok.com') {
            try {
                // Initialize GuzzleHTTP client
                $client = new Client();

                // Perform a GET request to the URL using GuzzleHTTP
                $response = $client->get($this->url);

                // Extract HTML using Simple HTML DOM from response body
                $simpleHTMLDom = str_get_html($response->getBody());

                // TikTok removed og:url and most of OG meta tags and moved to JSON
                // Objects included in the video page at the very bottom.
                // The script id is: [__UNIVERSAL_DATA_FOR_REHYDRATION__]
                // We are searching for this script in the extracted HTML using ID
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
                    // of the given video. This is the clean video URL without any additional
                    // parameters because we do not need them.
                    $seo = json_encode($object['seo.abtest'], true);

                    // Get the JSON object named [webapp.video-details] which contains the full details
                    // of the given video.
                    $details = json_encode($object['webapp.video-detail'], true);

                    // Decode the contents of [seo.abtest] JSON Object
                    $seoDec = json_decode($seo);

                    // Decode the contents of [webapp.video-details] JSON Object
                    $detailsDec = json_decode($details);

                    // Video canonical URL
                    $canonical = $seoDec->canonical;

                    // Video ID
                    $video_id = $detailsDec->itemInfo->itemStruct->id;

                    // Video Description
                    $video_desc = $detailsDec->itemInfo->itemStruct->desc;

                    // Video Author Name
                    $user = $detailsDec->itemInfo->itemStruct->author->nickname;

                    // Video Author Username
                    $username = $detailsDec->itemInfo->itemStruct->author->uniqueId;

                    // Video Author ID
                    $user_id = $detailsDec->itemInfo->itemStruct->author->id;

                    // Static Video Thumbnail
                    $thumbnail_static = $detailsDec->itemInfo->itemStruct->video->cover;

                    // Dynamic Video Thumbnail
                    $thumbnail_gif = $detailsDec->itemInfo->itemStruct->video->dynamicCover;

                    // Here we need to check if any thumbnails (static and dynamic) is null
                    // to prevent any error, because some videos do not have a dynamic one.
                    $thumbnail = ($thumbnail_gif !== null) ? $thumbnail_gif : $thumbnail_static;

                    // Video Views Count
                    $video_views = $detailsDec->itemInfo->itemStruct->stats->playCount;

                    // Video Likes Count
                    $video_likes = $detailsDec->itemInfo->itemStruct->stats->diggCount;

                    // Video Comments Count
                    $video_comments = $detailsDec->itemInfo->itemStruct->stats->commentCount;

                    // Video Shares Count
                    $video_shares = $detailsDec->itemInfo->itemStruct->stats->shareCount;

                    // Video Favorites Count
                    $video_favorites = $detailsDec->itemInfo->itemStruct->stats->collectCount;

                    // Now everything is done! Return the video details.
                    return array(
                        'status' => 'ok',
                        'link' => $canonical,
                        'user' => $user,
                        'username' => $username,
                        'user_id' => $user_id,
                        'video_id' => $video_id,
                        'video_desc' => $video_desc,
                        'thumbnail' => $thumbnail,
                        'views' => $video_views,
                        'likes' => $video_likes,
                        'comments' => $video_comments,
                        'shares' => $video_shares,
                        'favorites' => $video_favorites,
                    );
                }
            } catch (GuzzleException $e) {
                // Return error message with details
                return array(
                    'status' => 'error',
                    'message' => 'Something went wrong: ' . $e->getMessage(),
                );
            }
        } else {
            // Return error message if the URL is invalid
            return array(
                'status' => 'error',
                'message' => 'Please enter a valid TikTok URL!',
            );
        }
    }
}

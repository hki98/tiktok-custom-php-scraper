# New Repo Moved Here
**This repo is no longer maintained, please use the new one below.**
- [New Repo](https://github.com/haianibrahim/tiktok-scraper)

# TikTok® Custom PHP Scraper


**Hi there!<br><br>I have many TikTok releated web projects and I was looking for a TikTok Custom PHP API or Scraper, All what I found was paid, limited scripts and most of them are in Python. I do not want so many features, I just want a simple script which I give it a TikTok Video URL then I want to get few details about it: Canonical URL, Username & Video ID That is it!<br><br>So I decided to write my own custom PHP script to get these details.<br><br>Maybe you guys want the same thing, I hope it will be useful for someone else. Enjoy!<br><br>Note: This script can be customized to scrape any additional data you want for a TikTok Video. I have only implemented the features that fits my needs.**

## Installation
 ```bash
composer require hki98/tiktok-custom-php-scraper
 ```

## Usage

Instantiate the `TikTokScraper` class with the TikTok video URL and then call the `scrapeVideoDetails()` method to get the details of the video.

```php
<?php

require 'vendor/autoload.php';

use hki98\TikTokScraper;

$url = $_GET['url']; // Assuming $url is obtained from user input
$scraper = new TikTokScraper($url);
$result = $scraper->scrapeVideoDetails();
echo json_encode($result, JSON_PRETTY_PRINT);
```

## Returned Data:
As I mentioned above, I have made this script to only scrape the data that I need. So, It will return only these values for the given TikTok video URL:
```json
{
  "status": "ok",
  "link": "https://www.tiktok.com/@tiktok/video/7353002700935679278",
  "user": "TikTok",
  "username": "tiktok",
  "user_id": "107955",
  "video_id": "7353002700935679278",
  "video_desc": "This is your sign to keep doing the things that ignite your soul ✨ You’re destined to be your best self. #WomensMonth #PowerOfWe #JustLikeThat #Manifesting",
  "thumbnail": "https://p16-sign.tiktokcdn-us.com/obj/tos-useast5-p-0068-tx/owxlSFD9VCDevQAvkWEIjzCAOA1gdzARCmfOVA?x-expires=1712268000&x-signature=oZRVD2O2Kr1hr96YoN2r%2F15GKoM%3D",
  "views": 78900,
  "likes": 1413,
  "comments": 297,
  "shares": 64,
  "favorites": "112"
}
```

**Supported Links:**
  - https://www.tiktok.com/@username/video/012345678901234
  - https://vt.tiktok.com/aBcDeFgH
  - https://vm.tiktok.com/aBcDeFgH

## Used:
- [PHP](https://php.net/)
- [Composer](https://getcomposer.org/)
- [GuzzleHTTP](https://docs.guzzlephp.org/en/stable/)
- [Simple HTML DOM](https://simplehtmldom.sourceforge.io/docs/1.9/index.html)

## Notice: TikTok is a Trademark of ByteDance Ltd.

This project is not affiliated with, endorsed, or sponsored by ByteDance Ltd., the creator of TikTok. TikTok® is a registered trademark of ByteDance Ltd.

The use of the TikTok name, logo, or any related trademarks or intellectual property is for descriptive purposes only, and it does not imply any endorsement or affiliation with this project.

We respect intellectual property rights and are committed to ensuring that our project complies with all applicable copyright and trademark laws. If you believe that any content on this platform infringes on TikTok's trademark or copyright, please contact us promptly so that we can address the concern.

We do not intend to infringe upon any copyrights or trademarks associated with TikTok, and any use of TikTok-related terms is solely for the purpose of providing information and services related to the TikTok platform.

Thank you for your understanding and cooperation.

---
I'm open to work, let's connect: [Haian K. Ibrahim](https://linkedin.com/in/haian-k-ibrahim) | [contact [at] haian.me](mailto:contact@haian.me)

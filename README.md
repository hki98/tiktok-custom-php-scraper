# TikTok® Custom PHP Scraper

## Description:

**Hi there!<br><br>I have many TikTok releated web projects and I was looking for a TikTok Custom PHP API or Scraper, All what I found was paid, limited scripts and most of them are in Python. I do not want so many features, I just want a simple script which I give it a TikTok Video URL then I want to get few details about it: Canonical URL, Username & Video ID That is it!<br><br>So I decided to write my own custom PHP script to get these details.<br><br>Maybe you guys want the same thing, I hope it will be useful for someone else. Enjoy!<br><br>Note: This script can be customized to scrape any additional data you want for a TikTok Video. I have only implemented the features that fits my needs.**

## Usage:
- **Step 1**:
  ```bash
  git clone https://github.com/hki98/tiktok-custom-php-scraper.git
  ```

- **Step 2**:
  Upload the downloaded files to your web hosting account.
  
- **Step 3**:
  Make sure to edit this:
  ```php
  header("Access-Control-Allow-Origin: YOUR_DOMAIN_HERE");
  ```
And that's all!

- Now you can test the script by sending a request to:
  ```php
  https://yourdomain.com/path_to_api/api.php?url=[TIKTOK_VIDEO_URL]
  ```

## Returned Data:
As I mentioned above, I have made this script to only scrape the data that I need. So, It will return only three values for the given TikTok video URL:
```json
{
  "status": "ok",
  "link": "https://www.tiktok.com/@hki98/video/0123456789012345678",
  "username": "hki98",
  "video_id": "0123456789012345678",
}
```

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

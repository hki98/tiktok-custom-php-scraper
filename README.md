# TikTok® Custom PHP Scraper

### Description:

## Hi there! I have many TikTok releated web projects and I was looking for a TikTok Custom PHP API or Scraper, All what I found was paid, limited scripts and most of them are in Python. I do not want so many features, I just want a simple script which I give it a TikTok Video URL then I want to get few details about it: Canonical URL, Username & Video ID That is it!<br>So I decided to write my own custom PHP script to get these details.<br>Maybe you guys want the same thing, I hope it will be useful for someone else. Enjoy!<br>Note: This script can be customized to scrape any additional data you want for a TikTok Video. I have only implemented the features that fits my needs.

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

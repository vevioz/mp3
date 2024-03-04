<?php
class YouTubeDownloader{
    
    protected $client;
    public $title;
    public $id;
    public $error;

    function __construct(){
        $this->client = new Browser();
    }

    public function getBrowser(){
        return $this->client;
    }

    public function getLastError(){
        return $this->error;
    }

    public function getPlayerScriptUrl($video_html){
        $player_url = null;
        if (preg_match('@<script\s*src="([^"]+player[^"]+js)@', $video_html, $matches)) {
            $player_url = $matches[1];
            if (strpos($player_url, '//') === 0) {
                $player_url = 'http://' . substr($player_url, 2);
            } elseif (strpos($player_url, '/') === 0) {
                // relative path?
                $player_url = 'http://www.youtube.com' . $player_url;
            }
        }
        return $player_url;
    }

    public function getPlayerCode($player_url){
        $contents = $this->client->getCached($player_url);
        return $contents;
    }

    public function extractVideoId($str){
        if (preg_match('/[a-z0-9_-]{11}/i', $str, $matches)) {
            return $matches[0];
        }
        return false;
    }

    private function selectFirst($links, $selector){
        $result = array();
        $formats = preg_split('/\s*,\s*/', $selector);
        // has to be in this order
        foreach ($formats as $f){
            foreach ($links as $l){
                //if (stripos($l['itag'], $f) !== false || $f == 'any') {
                if($l['itag'] == $f || $f == 'any'){
                    $result[] = $l;
                }
            }
        }
        return $result;
    }

    /*
    public function getVideoInfo($video_id){
        $response = $this->client->get("https://www.youtube.com/get_video_info?html5=1&c=TVHTML5&cver=6.20180913&" . http_build_query([
                'video_id' => $video_id,
                'eurl' => 'https://youtube.googleapis.com/v/' . $video_id,
                'el' => 'embedded' // or detailpage. default: embedded, will fail if video is not embeddable
            ]));
        if ($response) {
            $arr = array();
            parse_str($response, $arr);
            if (array_key_exists('player_response', $arr)) {
                $arr['player_response'] = json_decode($arr['player_response'], true);
            }
            return $arr;
        }
        return null;
    }
    */

    public function getPlayerConfig($video_id){
        $video_id = $this->extractVideoId($video_id);
        $response = $this->client->get("https://www.youtube.com/watch?v={$video_id}");
        if (preg_match('/ytplayer.config\s*=\s*([^\n]+});ytplayer/i', $response, $matches)) {
            return json_decode($matches[1], true);
        }
        return [];
    }

    public function getPageHtml($url){
        $video_id = $this->extractVideoId($url);
        $this->id = $video_id;
        return $this->client->get("https://www.youtube.com/watch?v={$video_id}");
    }

    public function getPlayerResponse($page_html){
        if (preg_match('/player_response":"(.*?)\"}};/', $page_html, $matches)) {
            $match = stripslashes($matches[1]);
            $ret = json_decode($match, true);
            return $ret;
        }
        return null;
    }

    public function parsePlayerResponse($player_response, $js_code){
        $parser = new Parser();
        try {
            $formats = Utils::arrayGet($player_response, 'streamingData.formats', []);
            // video only or audio only streams
            $adaptiveFormats = Utils::arrayGet($player_response, 'streamingData.adaptiveFormats', []);
            $this->title = $this->clean_name(Utils::arrayGet($player_response, 'videoDetails.title', []));
            $formats_combined = array_merge($formats, $adaptiveFormats);
            // final response
            $return = array();
            foreach ($formats_combined as $item){
                //print_r($item);
                // sometimes as appear as "cipher" or "signatureCipher"
                $cipher = Utils::arrayGet($item, 'cipher', Utils::arrayGet($item, 'signatureCipher', ''));
                $itag = $item['itag'];
                // some videos do not need to be decrypted!
                if (isset($item['url'])) {
                    $return[] = array(
                        'url' => $item['url'],
                        'itag' => $itag,
                        'format' => $parser->parseItagInfo($itag)
                    );
                    continue;
                }
                parse_str($cipher, $result);
                $url = $result['url'];
                $sp = $result['sp']; // typically 'sig'
                $signature = $result['s'];
                $decoded_signature = (new SignatureDecoder())->decode($signature, $js_code);
                // redirector.googlevideo.com
                $return[] = array(
                    'url' => $url . '&' . $sp . '=' . $decoded_signature,
                    'itag' => $itag,
                    'format' => $parser->parseItagInfo($itag)
                );
            }
            return $return;
        } catch (\Exception $exception) {
            // do nothing
        } catch (\Throwable $throwable) {
            // do nothing
        }
        return null;
    }
    
    public function clean_name($name) {
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", chr(0));
        $filename = str_replace($special_chars,' ',$name);
        $filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
        $filename = str_replace( array( '%20', '+'), '-', $filename );
        $filename = preg_replace( '/[\r\n\t-]+/', '-', $filename );
        $filename = trim($filename, '.-_' );
        return $filename;
    }
    
    public function getVideoInfo($video_id){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/youtubei/v1/player?key=AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{
            "context": {
                "client": {
                    "hl": "en",
                    "clientName": "WEB",
                    "clientVersion": "2.20210721.00.00",
                    "clientFormFactor": "UNKNOWN_FORM_FACTOR",
                    "clientScreen": "WATCH",
                    "mainAppWebInfo": {
                        "graftUrl": "/watch?v='.$video_id.'",
                    }
                },
                "user": {"lockedSafetyMode": false},
                "request": {
                    "useSsl": true,
                    "internalExperimentFlags": [],
                    "consistencyTokenJars": []
                }
            },
            "videoId": "'.$video_id.'",
            "playbackContext": {
                "contentPlaybackContext": {
                    "vis": 0,
                    "splay": false,
                    "autoCaptionsDefaultOn": false,
                    "autonavState": "STATE_NONE",
                    "html5Preference": "HTML5_PREF_WANTS",
                    "lactMilliseconds": "-1"
                }
            },
            "racyCheckOk": false,
            "contentCheckOk": false
        }');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result, true);
    }
    
    public function getDownloadLinks($video_id, $selector = false){
        $this->error = null;
        $page_html = $this->getPageHtml($video_id);
        if (strpos($page_html, 'We have been receiving a large volume of requests') !== false ||
            strpos($page_html, 'systems have detected unusual traffic') !== false ||
            strpos($page_html, '/recaptcha/') !== false) {
            $this->error = 'HTTP 429: Too many requests.';
            return array();
        }

        // get JSON encoded parameters that appear on video pages
        $json = $this->getPlayerResponse($page_html);
        
        if (empty($json)) {
            $json = $this->getVideoInfo($this->extractVideoId($video_id));
            //$json = Utils::arrayGet($json, 'player_response');
        }
        // get player.js location that holds signature function
        $url = $this->getPlayerScriptUrl($page_html);
        $js = $this->getPlayerCode($url);

        $result = $this->parsePlayerResponse($json, $js);

        // if error happens
        if (!is_array($result)) {
            return array();
        }

        // do we want all links or just select few?
        if ($selector) {
            return $this->selectFirst($result, $selector);
        }

        return $result;
    }
}
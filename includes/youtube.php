<?php
function isValidYouTubeUrl($url) {
    $pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

    if (preg_match($pattern, $url, $matches)) {
        return true;
    } else {
        return false;
    }
}
function getYoutubeVideoId($url) {
    // Parse the URL
    $urlParts = parse_url($url);

    // Check if it's a valid YouTube URL
    if ($urlParts === false || empty($urlParts['query'])) {
        return false; // Invalid URL
    }

    // Parse the query string
    parse_str($urlParts['query'], $queryParams);

    // Check if the 'v' parameter is present
    if (isset($queryParams['v'])) {
        return $queryParams['v']; // Return the video ID
    } else {
        return false; // 'v' parameter not found
    }
}
if(isset($_POST['url']) && isValidYouTubeUrl($_POST['url'])){
    ?>
<script>iFrameResize({ log: false }, '#buttonApi1');iFrameResize({ log: false }, '#buttonApi2')</script>
    <?php
    echo '<div class="download-info"><iframe width="100%" height="315" src="https://www.youtube.com/embed/'.getYoutubeVideoId($_POST['url']).'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe><hr><iframe id="buttonApi1" src="https://api.vevioz.com/apis/button/mp4?url='.urlencode($_POST['url']).'&ts='.time().'"
width="100%" height="100%" allowtransparency="true" scrolling="no" style="border:none"></iframe><br><br><iframe id="buttonApi2" src="https://api.vevioz.com/apis/button/mp3?url='.urlencode($_POST['url']).'&ts='.time().'"
width="100%" height="100%" allowtransparency="true" scrolling="no" style="border:none"></iframe><hr><a href="/"><button type="button" style="margin: 5px;" class="btn btn-primary">Download Another Video</button></a></div>';
}else{
?>
<div class="download-info"> <p>Invalid Youtube URL.<br/></p> <a href='/'><button type='button' style='margin: 5px;' class='btn btn-primary'>Download Another Video</button></a> </div>
<?php 
}
?>
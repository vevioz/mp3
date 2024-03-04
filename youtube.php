<?php
if(isset($_REQUEST['yt'])){
    echo '<iframe id="buttonApi" src="https://api.vevioz.com/apis/button/mp3?url="'.urlencode($_REQUEST['yt']).'
width="100%" height="100%" allowtransparency="true" scrolling="no" style="border:none"></iframe>';
}
?>
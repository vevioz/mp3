<?php require "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<div id="g_id_onload"
     data-client_id="481913602305-bddp4b8u9i1bi1cpe3ceeou5nk1ekrsl.apps.googleusercontent.com"
     data-auto_select="true"
     data-login_uri="https://www.vevioz.com/login-with.php?provider=Google"
	 data-cancel_on_tap_outside="false">
</div>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $config['site_title']; ?></title>
    <meta name="description" content="<?php echo $config['site_meta']; ?>">
    <meta name="keywords" content="<?php echo $config['site_meta']; ?>">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png" />
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/slicknav.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.3.2/iframeResizer.min.js"></script>
    <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>
<body>
<div class="container">
<header>
			<div class="row">
				<div class="col-md-3 col-sm-3 logo">
				<a href="<?php echo $config['site_url']; ?>"><img src="img/logo.png" alt=""></a>
				</div>
				<div class="col-md-9 col-sm-9 nav-wrapper">
				<nav>
			<ul class="sf-menu" id="menu">
			    <li><a href="/">HOME</a></li>
			    <li><a href="https://addons.vevioz.com" target="_blank">ADDONS</a></li>
			    <li><a href="https://api.vevioz.com" target="_blank">API</a></li>
			    <li><a href="https://server.vevioz.com" target="_blank">SERVER</a></li>
			    <li><a href="https://status.vevioz.com" target="_blank">STATUS</a></li>
			    <li><a href="https://www.trustpilot.com/review/vevioz.com" target="_blank">REVIEW</a></li>
			    </ul>
				</nav>
				</div>
			</div>
		</div>
	</header>
    <div class="noPadding center container-fluid">
      <div class="bg" style="min-height:550px;">
        <div class="topBannerHome">
          <div class="siteHeading">
          <h1 class="pageTitle"  >YouTube Downloader MP3 and MP4</h1>
          <h2 class="pageSubtitle"  >Download videos from YouTube for free!</h2>
        </div>
        <div class="container maxWidth">
        <?php if($config['enable_ads'] == true) {
  		echo $config['ads_code'];
  		} ?><br><br>
                  <div id="searchVideo">
                    <div class="row">
                      <div class="col-md-12 abc">
                        <?php if(isset($_GET['v']) && ($_GET['v']) != ''){ ?>
<input type="hidden" name="url" id="video_url" class="form-control" placeholder="Video Link" value="https://www.youtube.com/watch?v=<?php echo $_GET['v']; ?>" required>
<script>$(document).ready(function() { youtube(); });</script>
                         <?php } else { ?>
<form onsubmit="youtube()" id="downloadForm" class="search-form" method="POST" action="javascript:void(0);">
                            <input name="video_url" id="video_url" class="url-input" type="text" placeholder= "Enter the video link..."  />
                              <div onclick="youtube()" class="image">
                                <span class="helper"></span><img src="img/download_icon.svg" />
                              </div>
                            </form>
				<?php } ?>
                    </div></div><br><br>
                    <?php if($config['enable_ads'] == true) {
  		echo $config['ads_code'];
  		} ?>
              </div>
          </div>
        </div>
      </div>
    </div>
<footer>
  <span class="copyright">Copyright © 2021 <?php echo $config['site_title']; ?>. All Rights Reserved.</span>
</footer>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.slicknav.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Contact Us</h4>
                    </div>
                    <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">Full Name:
                                </label>
                                <input type="text" class="form-control" id="name">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Email Address:</label>
                                <input type="email" class="form-control" id="email">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Subject:
                                </label>
                                <input type="text" class="form-control" id="subject">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Message:
                                </label>
                                <textarea class="form-control" rows="5" id="message"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                    	<div class="ajax-loadingc"><img src="img/loading.gif" alt="loading..."></div>
                        <button type="button" id="submit" class="btn btn-primary contactform">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
              </div>
       </div>
</div>
</body>
</html>

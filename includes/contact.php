<?php
require_once "../config.php";
    $valid = 1;
    $error_message = "";
    if(empty($_POST['name'])) {
        $valid = 0;
        $error_message .= "Full Name can not be empty<br>";
    }
    if(empty($_POST['email'])) {
        $valid = 0;
        $error_message .= 'Email address can not be empty<br>';
    } else {
    	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
	        $valid = 0;
	        $error_message .= 'Email address must be valid<br>';
	    } 
    }
    if(empty($_POST['subject'])) {
        $valid = 0;
        $error_message .= "Subject can not be empty<br>";
    }
    if(empty($_POST['message'])) {
        $valid = 0;
        $error_message .= "Message can not be empty<br>";
    }
    if($valid == 1) {
    		// Sending Email
    		$to = $config['contact_email'];
		$from = $config['server_email'];
		$subject = 'Contact Email';
		$message = 'You have been sent a contact mail on '.$config['site_title'].'<br><br>Sender Name: '.$_POST['name'].'<br><br>Sender Email: '.$_POST['email'].'<br><br>Subject: '.$_POST['subject'].'<br><br>Message: '.$_POST['message'];
		$headers = 'From: ' . $from . "\r\n" .'Reply-To: ' . $_POST['email'] . "\r\n" .'X-Mailer: PHP/' . phpversion() . "\r\n" . "MIME-Version: 1.0\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n";
		// Sending the email
		mail($to, $subject, $message, $headers);
?>
<div id="contactsuccess" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Successful</h4>
      </div>
      <div class="modal-body">
        <p>Your message have been sent successfully.<br>We will get back to you as soon as possible!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php } else { ?>
<div id="contactsuccess" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Failure</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $error_message; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>
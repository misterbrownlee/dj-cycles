 <?php
 
 	// script to validate recaptcha, then send email if recaptcha passes
 
 	require_once('recaptchalib.php');
 
 	$privateKey = "6Lc4XbsSAAAAAGqqoE92FrwfmfllV58xPm16eVIZ";
 	
 	// do the recaptcha validation
 	$response = recaptcha_check_answer($privateKey,
                               $_SERVER["REMOTE_ADDR"],
                               $_POST["recaptcha_challenge_field"],
                               $_POST["recaptcha_response_field"]);
    
    $emailSent = false;
    
    // if recaptcha passed send email, otherwise return error
 	if (isValid($response)) {
 
 		$emailSent = sendEmail();
 		
 	} else {

		$errorCode = $response->error;
 		displayError($errorCode);
 	
 	}
 	
 	// check mail send was successful
	if ($emailSent) {
	
		displayThanks();
		
	} else {
	
		// not handling the email error on the contact form a this time 
		die ("Sorry, the email system has encountered an error!" .
		  "Please return to <a href=\"http://dj-cycles.com\">dj-cycles.com</a><br/><br/>" .
		  "Remember, the best way to reach DJ Cycles is by phone.");
	}
  
  
	// -------   FUNCTIONS -----------
  
	function displayThanks() {
   		// redirect to thanks page
 		print "<meta http-equiv=\"refresh\" content=\"0;URL=..\\thanks.html\"\">";

 	}
 	

	function displayError($errorCode) {
	
   		// redirect back to contact page with error code in URL
   		$firstName = $_POST['firstName']; 
		$lastName = $_POST['lastName']; 
		$telephone = $_POST['phone']; 
		$email = $_POST['email']; 
		$message = $_POST['mailText'];
		
   		$parameters = "?errorCode=".$errorCode; 
   		$parameters .= "&firstName=".$firstName;
   		$parameters .= "&lastName=".$lastName;
   		$parameters .= "&phone=".$telephone;
   		$parameters .= "&email=".$email;
   		$parameters .= "&mailText=".$message;
   		
   		print "<meta http-equiv=\"refresh\" content=\"0;URL=..\\contact.html".$parameters."\">";

 	} 
 
 
	function isValid($response) {
   		return $response->is_valid;
	}

 
	function sendEmail() {

		$emailTo = "aaron.brownlee@gmail.com, djenks63@yahoo.com";
		$subject = "[dj-cycles] message from contact form";
		$headers = "From: do_not_reply@dj-cycles.com" . "\r\n" .
    		"Reply-To: do_not_reply@dj-cycles.com" . "\r\n" .
    		"X-Mailer: PHP/" . phpversion();
	
		$body = createBody();
		
		// send email 
		$success = mail($emailTo, $subject, $body, $headers, "-fdo_not_reply@dj-cycles.com");

		return $success;

	}
	
	
	function createBody() {
	
		$firstName = Trim(stripslashes($_POST['firstName'])); 
		$lastName = Trim(stripslashes($_POST['lastName'])); 
		$telephone = Trim(stripslashes($_POST['phone'])); 
		$email = Trim(stripslashes($_POST['email'])); 
		$message = Trim(stripslashes($_POST['mailText'])); 

		// prepare email body text
		$body = "The following was sent from the contact form at dj-cycles.com";
		$body .= "\n------------------------------------------------\n";
		$body .= "Name: ";
		$body .= $firstName." ".$lastName;
		$body .= "\n";
		$body .= "Telephone: ";
		$body .= $telephone;
		$body .= "\n";
		$body .= "Email: ";
		$body .= $email;
		$body .= "\n";
		$body .= "Message: ";
		$body .= $message;
		$body .= "\n";
		
		
		return $body;
	}
		
			
		
		 ?>
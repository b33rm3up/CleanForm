<?php
session_start();
$FormToken = bin2hex(random_bytes(64));

function CleanForm($FormData) {
    $FormData = trim($FormData);
    $FormData = stripslashes($FormData);
    $FormData = htmlspecialchars($FormData);
    
    return $FormData;
}

#empty form placeholders
$FormErrorName = '';
$FormErrorEmail = '';
$FormErrorPhone = '';
$FormErrorMessage = '';
$FormErrorCaptcha = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $FormValid = true;
    
    #data we are passing along
    $FormCustomerName = CleanForm($_POST['CustomerName']);
    $FormCustomerEmail = CleanForm($_POST['CustomerEmail']);
    $FormCustomerPhone = CleanForm($_POST['CustomerPhone']);
    $FormCustomerMessage = CleanForm($_POST['CustomerMessage']);
    $FormCaptcha = CleanForm(strtoupper($_POST['FormCaptcha']));

         #validate Customer Name, insure it does not contain special characters or numbers
         if (empty($FormCustomerName)) {
             $FormErrorName = 'Name is required!';
             $FormValid = false;

             $FormErrorNameState = 'has-error';
         } else {
             $ValidateCustomerName = $FormCustomerName;

             if (!preg_match("/^[a-zA-Z ]*$/", $ValidateCustomerName)) {
                 $FormErrorName = "Invalid name Format!";
                 $FormValid = false;

                 $FormErrorNameState = 'has-error';
             }
         }
         ##################################################################################

         #validate Customer Email, insure it does not contain special characters or numbers
         if (empty($FormCustomerEmail)) {
             $FormErrorEmail = 'Email is required!';
             $FormValid = false;

             $FormErrorEmailState = 'has-error';
         } else {
             $ValidateCustomerEmail = $FormCustomerEmail;

             if (!filter_var($ValidateCustomerEmail, FILTER_VALIDATE_EMAIL)) {
                 $FormErrorEmail = "Invalid email Format!";
                 $FormValid = false;

                 $FormErrorEmailState = 'has-error';
             }
         }
         ################################################################################### 
         
         #validate Customer Phone, insure it does not contain letters or characters
         if (empty($FormCustomerPhone)) {
             $FormErrorPhone = 'Phone number is required!';
             $FormValid = false;

             $FormErrorPhoneState = 'has-error';
         } else {
             #$ValidateCustomerPhone = preg_replace('/[^0-9]/', '', $FormCustomerPhone);
             $ValidateCustomerPhone = filter_var($FormCustomerPhone, FILTER_SANITIZE_NUMBER_INT);

             if (strlen($ValidateCustomerPhone) <= 10) {
                 $FormErrorPhone = "Invalid phone Format!";
                 $FormValid = false;

                 $FormErrorPhoneState = 'has-error';
             }
         }
         ###################################################################################

         #validate Customer Message, insure it does not contain URL's and spam words
         if (empty($FormCustomerMessage)) {
             $FormErrorMessage = 'Message is required!';
             $FormValid = false;

             $FormErrorMessageState = 'has-error';
         } else {
             $ValidateCustomerMessage = $FormCustomerMessage;

             if (preg_match("/\b(?:(?:https?|ftp|http):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $ValidateCustomerMessage)) {
                 $FormErrorMessage = 'Content not allowed! - ERR-UR';
                 $FormValid = false;

                 $FormErrorMessageState = 'has-error';
             }

             if (preg_match('/viagra|poker|sex|adult|porn|Porn|Sex|SeX|SEX|xxx|p0rn|lead|PORN|LINK|MARKETING|click|marketing|advertisment|link|seo|se0|promotion|buy|sell|trade|iphone|android|http|https|www/', $ValidateCustomerMessage)) {
                 $FormErrorMessage = 'Content not allowed! ERR-SW';
                 $FormValid = false;

                 $FormErrorMessageState = 'has-error';
             }
         }
         ###################################################################################

         #validate CAPTCHA data >>> if (!empty($FormCaptcha) && $FormCaptcha == $_SESSION['CaptchaText']) {
         if (empty($FormCaptcha)) {
             $FormErrorCaptcha = 'CAPTCHA is required!';
             $FormValid = false;

             $FormErrorCaptchaState = 'has-error';
         } else {

             if ($FormCaptcha != $_SESSION['CaptchaText']) {
                 $FormErrorCaptcha = 'Invalid CAPTCHA entered!';
                 $FormValid = false;

                 $FormErrorCaptchaState = 'has-error';
             }
         }
         ###################################################################################

         #if all data is Valid, process the form successfully
         if($FormValid) {
             header ("location: success.php");
             exit();
         }
         ####################################################
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <style>
    body { padding-top: 100px; }

    #FormCaptcha { text-transform: uppercase; }
    </style>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off" enctype="multipart/form-data">
                     <div class="col-sm-3 col-md-6 col-lg-4">
                         <div class="form-group <?php echo $FormErrorNameState ?? ''; ?>">
                             <label for="CustomerName">Name *</label>
                             <input id="CustomerName" name="CustomerName" type="text" class="form-control" placeholder="John Smith" required>
                             <span class="error-block help-block"><?php echo $FormErrorName ?? ''; ?></span>
                         </div>
                     </div>

                     <div class="col-sm-3 col-md-6 col-lg-4">
                         <div class="form-group <?php echo $FormErrorEmailState ?? ''; ?>">
                             <label for="CustomerEmail">Email *</label>
                             <input id="CustomerEmail" name="CustomerEmail" type="email" class="form-control" placeholder="example@example.abc" required>
                             <span class="error-block help-block"><?php echo $FormErrorEmail ?? ''; ?></span>
                         </div>
                     </div>

                     <div class="col-sm-3 col-md-6 col-lg-4">
                         <div class="form-group <?php echo $FormErrorPhoneState ?? ''; ?>">
                             <label for="CustomerPhone">Phone *</label>
                             <input id="CustomerPhone" name="CustomerPhone" type="text" class="form-control" placeholder="(000) 000-0000" pattern="\[\(]\d{3}[\) ]\d{3}[\-]\d{4}" required>
                             <span class="error-block help-block"><?php echo $FormErrorPhone ?? ''; ?></span>
                         </div>
                     </div>

                     <div class="col-sm-12 col-md-12 col-lg-12">
                         <div class="form-group <?php echo $FormErrorMessageState ?? ''; ?>">
                             <label for="CustomerMessage">Message *</label>
                             <textarea id="CustomerMessage" name="CustomerMessage" class="form-control" rows="4" placeholder="Message" required></textarea>
                             <span class="error-block help-block"><?php echo $FormErrorMessage ?? ''; ?></span>
                         </div>
                     </div>

                     <div class="col-sm-3 col-md-6 col-lg-4">
                         <div class="form-group <?php echo $FormErrorCaptchaState ?? ''; ?>">
                             <label for="FormCaptcha">Please Enter the CAPTCHA Text</label>
                             <img src="captcha.php" alt="CAPTCHA" title="CAPTCHA" class="captcha-image">&nbsp;<span class="glyphicon glyphicon-refresh btn btn-lg btn-default refresh-captcha" aria-hidden="true"></span>
                             <br><br>
                             <input id="FormCaptcha" name="FormCaptcha" type="text" class="form-control" pattern="[A-Za-z]{6}" placeholder="CAPTCHA" required>
                             <span class="error-block help-block"><?php echo $FormErrorCaptcha ?? ''; ?></span>
                         </div>
                     </div>

                     <div class="col-sm-12 col-md-12 col-lg-12">
                         <div class="form-group">
                             <button id="FormProcess" name="FormProcess" type="submit" class="btn btn-md btn-primary">Process Form</button>
                             <button id="FormClear" name="FormClear" type="reset" class="btn btn-md btn-danger">Clear Form</button>
                         </div>
                     </div>
                 </form>
             </div>
         </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <script src="https://rawgit.com/RobinHerbots/Inputmask/5.x/dist/jquery.inputmask.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
         $(":input").inputmask();
         $("#CustomerPhone").inputmask({"mask": "(999) 999-9999"});
    });

    var refreshButton = document.querySelector(".refresh-captcha");
    refreshButton.onclick = function() {
        document.querySelector(".captcha-image").src = 'captcha.php?' + Date.now();
    }
    </script>
  </body>
</html>
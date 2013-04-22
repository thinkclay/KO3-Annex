<html>
<head>
  <title>Qwizzle Registration</title>
</head>
<body>
  <p>Thank you for registering to Qwizzle.  Please click the following link to activate your account.</p>
  <?php echo HTML::anchor(url::site('user/complete_registration').'?token='.urlencode($token), 'Complete Registration'); ?>
  <p>If that doesn't work, copy and paste this link into your browser address bar:</p>
  <?php echo url::site('user/complete_registration').'?token='.urlencode($token); ?>
</body>
</html>
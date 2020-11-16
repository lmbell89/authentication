<h1>Authentication</h1>

<p>Social logins, regular auth and password resets via email.</p>

<p>A working demo is available at https://liambell.info/portfolio/auth </p>

<h2>Description</h2>

<p>
This PHP app allows authentication by users. 
Users can authenticate using a regular username and password 
or by signing in with Facebook or Google.
</p>

<p>
The logged in status of the user is stored in session using PHP's session_start. <br />
Minimum password complexity is enforced. If a user has forgotten their password 
they can reset it, by receiving a password reset link to the email address they registered with. <br />
User records are stored in SQL.
</p>

<p>
If a user attempts to access a page that requires them to be logged in, they will be directed back to the login screen. <br />
If the user provides valid credentials they will be redirected back to the page they attempted to access.
</p>

<p>
Bootstrap is used to style the pages and form validation. <br />
Some pages use a flash to show messages to the user.
</p>

<h2>Prerequisites</h2>

<p>
The project requires PHP 7.2+ with the PDO extension enabled. <br/ >
SMTP is required to send password reset links via email. <br />
SQL is required to store user records. <br />
A Facebook app_id and Facebook app_secret. See https://developers.facebook.com/ for more information. <br />
A Google client_id. See https://developers.google.com/identity for more information. <br />
</p>

<h2>Configuration</h2>

<p>
Most details, such as SMTP and SQL details, need to be entered \config\config.php <br />
The Facebook app_id and Google client_id must also be entered at the top of  \public\login.js <br />
The path to the autoload.php file for vendor is list at the top of \config\config.php
</p>
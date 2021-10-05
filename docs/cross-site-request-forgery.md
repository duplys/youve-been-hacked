# Cross-Site Request Forgery (CSRF)

## Introduction
Cross-Site Request Forgery (CSRF) attacks **exploit web application's trust in Alice** to perform unauthorized actions on her behalf. Eve's goal is to trick Alice into unknowingly submitting an HTTP/web request to a web application that Alice has privileged access to (but Eve does not). Unlike Cross-Site Scripting (XSS) which exploits the trust a user has for a particular site, CSRF exploits he trust that a site has in a user's agent (e.g., user's browser).

At risk are web applications that perform actions based on input from trusted and authenticated users without requiring the user to authorize the specific action. For instance, a user who is authenticated by a cookie saved in the user's web browser could unknowingly send an HTTP request to a site that trusts the user and thereby cause an unwanted action.

Essentially, it is sufficient to trick an authorized user to send a maliciously crafted HTTP request. As a result, CSRF is not limited to web pages &mdash; any document format that supports scripting can be exploited, e.g., HTML e-mails and multimedia files.

### Example 1
* `www.server.xyz/app/index.php?action=logout` is used to log out users
* The web application identifies users through a session-ID stored in a cookie
* The cookie is automatically sent by the user's web browser
* The web application is an online forum where links can be stored in posts
* Eve publishes a post with the above link disguised by an `<a href="...">more info here</a>`
* Alice clicks on the link and is logged out

### Example 2
* New user is added when `www.server.xyz/app/admin/adduser.php?name=NAME&pass=PWD` is called by an admin
* The web application identifies users through a session-ID stored in a cookie
* Eve prepares a malicious web page and tricks the admin to open this page
* The web page contains `<img src=www.server.xyz/app/admin/adduser.php?name=eve&pass=eve">`
* The (loged in via cookie) admin opens the malicious page
* Admin's web browser sends a `GET` request to load the image
* The URL to create a new user is called instead
* The cookie to authenticate the admin is automatically sent by her web browser

### Example 3
* Instead of a `GET` request, the web application uses a form &mdash; and, thus, a `POST` request &mdash; to add new users:
```
<form method="post" action="http://www.server.xyz/app/admin/adduser.php">
  Name: <input name="username"> <br>
  Passwort: <input name="password"> <br>
  <input type=submit value="Add User">
</form>
```

* The data sent to the application is in the `POST` request, i.e., it doesn't show in the URL. 
* Eve prepares a malicious web page and tricks the admin to open this page
* The web page contains a form that is auto-submitted upon opening the pages:
```
<form name="csrf" method="post" action="http://www.server.xyz/app/admin/adduser.php">
  <input type="hidden" name="username" value="eve">
  <input type="hidden" name="password" value="eve">
</form>
<script>document.CSRF.submit()</script>
 ```
* The (loged in via cookie) admin opens the malicious page
* The form is submitted automatically, as if the admin clicked on "Add User"
* The cookie to authenticate the admin is automatically sent by her web browser
* Eve prepares a malicious web page and tricks the admin to open this page
* The web page contains a form that is auto-submitted upon opening the pages:
 ```
<form name="csrf" method="post" action="http://www.server.xyz/app/admin/adduser.php">
  <input type="hidden" name="username" value="eve">
  <input type="hidden" name="password" value="eve">
</form>
<script>document.CSRF.submit()</script>
```
* The (logged in via cookie) admin opens the malicious page
* The form is submitted automatically, as if the admin clicked on "Add User"
* The cookie to authenticate the admin is automatically sent by her web browser
 
 
## Detection
A web application is susceptible to CSRF when it implements actions that can be triggered via a static URL (`GET` request) or a static `POST` request (like a static form). 

To prevent CSRF attacks, the corresponding URLs or forms (`POST` requests) contain the so-called **anti-CSRF-token**. The anti-CSRF-token is a random value that Eve cannot guess. The action associated with the `GET` or `POST` request is only performed if the token contains the correct value.

Thus, you need to check whether all `GET` and `POST` requests perform an action that requires authorization contain a CSRF-token. Check whether these requests contain parameters that change every time the request is sent. If any of these requests is static, i.e., doesn't change over time, the web application is vulnerable to CSRF.

## Defense
Add a **unique, random token** for every relevant `GET` and `POST` request. It is important that the token has sufficient entropy so that Eve cannot simply send multiple request with highly likely token values.

The web application must only perform actions when the **correct token** is supplied. Then, Eve cannot prepare a valid request because she can't guess the random token value (she could learn the token value using XSS, though).

For really important actions, add an additional **password check**.

## Demo


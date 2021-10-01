# Cookies

## Introduction
For a long time, cookies were the only option to **persistently store data on the client**, and it is still the prevailing solution.

Cookies are frequently used for **user identification**, e.g., at consecutive visits of a web application. Attacks based on cookie manipulation are called **cookie poisoning**.

## Cookie Types
Cookies can be persistent or non-persistent:
* **Persistent** cookies are stored on client's hard drive as long as their date is validated
* **Non-persistent** cookies are stored in RAM and are deleted when the web browser is closed

Moreover, cookies can be `secure` or `HttpOnly`:
* `secure` cookies are transmitted only via an HTTPS connection
* `HttpOnly` cookies are transmitted via HTTP or HTTPS, but cannot be accessed by JavaScript

## Storage and Manipulation
All web browsers store cookies in known file system locations and in known formats. An attacker can easily manipulate this data before it is used by a web application.

It is also possible to manipulate cookies **on the fly**, for example, using a tool like the ZAProxy.

Unlike with URL parameters, the attacker can also manipulate the date when the cookie expires, i.e., she can manipulate the cookie's lifetime.

In general, cookies can give you access to things like sessions, profiles, etc.

## Example
Web application under `https://www.weather.xyz` offers detailed weather information against payment. 

`https://www.weather.xyz` uses cookies that contain `userID` to identify users. The cookie is valid for the user's subscription period, e.g., a month.

Information stored in the cookie is processed by `https://www.weather.xyz` without additional authentication. 

This allows following 3 attacks:
* Attack 1: Eve manipulates `userID` to access `https://www.weather.xyz` as another user
* Attack 2: Eve extends their subscription by manipulating the cookie expiration date (by changing the Unix-timestamp in the cookie)
* Attack 3: cookies often store the number of failed login attempts. When they reach a specific threshold, say 5, `https://www.weather.xyz` deactivates that user account for 10 minutes to protect against brute force attacks. To bypass this, Eve sets the value of failed login attempts to 0 after each login attempt. Because she can automate this, she can trivially launch brute force attacks.

## Firefox
Here's cookies file in Firefox under Ubuntu Linux:
```shell
$ ls ~/.mozilla/firefox/3c4wnfog.default/cookies.sqlite
/home/paul/.mozilla/firefox/3c4wnfog.default/cookies.sqlite
```

To view the cookies, install `sqliteviewer`:

```shell
$ sudo apt install sqlitebrowser
-- snip --

Reading package lists... Done
Building dependency tree       
Reading state information... Done
The following additional packages will be installed:
  libqcustomplot1.3 libqt5scintilla2-12v5 libqt5scintilla2-l10n
The following NEW packages will be installed:
  libqcustomplot1.3 libqt5scintilla2-12v5 libqt5scintilla2-l10n sqlitebrowser
0 upgraded, 4 newly installed, 0 to remove and 3 not upgraded.
Need to get 1.677 kB of archives.
After this operation, 7.315 kB of additional disk space will be used.
Do you want to continue? [Y/n] 
Get:1 http://archive.ubuntu.com/ubuntu xenial/universe amd64 libqcustomplot1.3 amd64 1.3.2+dfsg1-1 [244 kB]

-- snip --
$ sqlitebrowser
```

![Example cookies](img/cookies/example-cookies.png "Example cookies")



## Detection
* For every cookie found during recon phase, you must check whether a parameter manipulation results in an illegal state change of the web application
* Manipulation should also include cookie parameters like expiration
* To detect cookie-related vulnerabilities more efficiently, \textit{decrease} the expiration date and check whether the web application denies its service
* As an alternative -- if you have access to the server-side source code of the web application -- check whether the source code uses the cookie expiration date as input parameter

## Defense
In principle, cookie poisoning **cannot** be eliminated
* Cookies are stored on the client
* Eve can always manipulate data on the client (even if that data belongs to other users)
* Two prominent attacks in this context are **cross-site-scripting** (XSS) and **cross-site-request-forgery** (CSRF)
* In a nutshell, Eve exploits web browser capabilities to set a cookie for a different domain

As a **general rule**, **never trust** data supplied by **the client**
* As an example, if you need to store a subscription's expiration date or the number of failed login attempts, store this information on the server
* If storing data on the client is unavoidable, encrypt or hash that data (do you understand why? if not, here is [a hint](https://en.wikipedia.org/wiki/Key_derivation_function#Password_hashing))

## Demo
Activate the breakpoint in ZAP and point your browser to `/app/index.php`. Step through and you should see something like this:

![PHPSESSID cookie](img/cookies/phpsessid-cookie.png "PHPSESSID cookie")

The vulnerable web app uses a cookie with the name `PHPSESSID` with the session of PHP. 


## References



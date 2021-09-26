# URL Parameters

## Introduction
URL parameters are used to transmit information in the URL, e.g., state information. In contrast to forms, transmitting information via URL parameters does not require clicking a submit button.

## Detection
Using URL parameters to transmit information poses a security risk, because it is trivial to manipulate these parameters. To identify vulnerabilities:
* Look for URL parameters identified during the recon phase
* Investigate what happens when you change these parameters. Does this cause an unexpected and unintended change of the state of the web application?

As an example, image you have this URL: `http://www.webapp.example/editprofile.php?id=123`:
* What happens when you change `id`? Can you edit the profile of a different user?
* Are there (hidden) parameters to activate debug information, e.g., `debug=on`, `debug=1` or `debug=true`?

## Defense
The core security issue with URL parameters is that the (state) information is stored **on the client** where it can be easily manipulated.

URL parameters must be therefore always be checked & validated. If possible, they should be encrypted or hashed.

## Demo

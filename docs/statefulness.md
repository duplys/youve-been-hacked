# Statefulness

## State in Web Applications
* HTTP, the protocol for transferring data between the server and the client, is **stateless**
* As an example, HTTP doesn't manage information about pages previously visited by the client, i.e., any URL can be requested by the client at any time
* If statefulness is required (e.g., page B shall only be served if the client has previously visited page A), the web application must manage a **session**

## Statefulness: A Web Shop Example
* Malicious user adds items into the shopping cart
* She then skips the page where credit card information must be entered and proceeds directly to the checkout page
* If the web application doesn't enforce the correct sequence of pages, the user would successfully place an order without having entered any payment details

## Managing State Information
There are two options to manage state information in web:
* State information is stored on the server; the users are identified using a **session ID**
* State information is stored on the client
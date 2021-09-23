# Hidden Input Fields

## Introduction
[A hidden field let web developers include data that cannot be seen or modified by users when a form is submitted][1].
Hidden input fields can contain state information: 

```html
<input name="id" value="1234" type="hidden">        
```

Such data fields can be easily manipulated using a tool like ZAP-Proxy or using web developer tools in the web browser. Thus, an attacker can easily manipulate hidden input fields.

## Detecting Hidden Input Field Vulnerabilities
For every hidden input field in the application, you must examine whether the web application's behavior changes if the values of these input fields are altered.

A classical anti-pattern (luckily not so common anymore) is to use hidden input fields for storing item price in a web shop. Another example is storing user's status, e.g., a signed in user instead of a "guest" or an administrator instead of a standard user.

## Defending Against Hidden Input Field Attacks
Core issue with hidden input fields: state information is stored **on the client** where it can be easily manipulated by a malicious user

Main defense philosophy: **never trust the client**

Critical information must always be stored on the server

Values received from the client must always be checked & validated

If values must be stored on the client (e.g., session IDs), they should be encrypted or hashed

## References
[1]: https://www.w3schools.com/tags/att_input_type_hidden.asp

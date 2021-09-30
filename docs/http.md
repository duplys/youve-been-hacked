# HTTP
## Introduction
Core specifications for the web:
* Uniform Resource Locators (URLs) 
  * [RFC 1738: Uniform Resource Locators (URL)][1]
  * [RFC 1808: Relative Uniform Resource Locators][2]
  * [RFC 2368: The mailto URL Scheme][3]
  * [RFC 3986: Uniform Resource Identifier (URI): Generic Syntax][4]
* Hypertext Markup Language (HTML)
  * [W3C HTML5 Specification][5]
* Hypertext Transfer Protocol (HTTP)
  * [RFC 7230: Hypertext Transfer Protocol (HTTP/1.1): Message Syntax and Routing][6]
  * [RFC 7231: Hypertext Transfer Protocol (HTTP/1.1): Semantics and Content][7]
  * [RFC 7232: Hypertext Transfer Protocol (HTTP/1.1): Conditional Requests][8]
  * [RFC 7233: Hypertext Transfer Protocol (HTTP/1.1): Range Requests][9]
  * [RFC 7234: Hypertext Transfer Protocol (HTTP/1.1): Caching][10]
  * [RFC 7235: Hypertext Transfer Protocol (HTTP/1.1): Authentication][11]
  * [RFC 7236: Initial Hypertext Transfer Protocol (HTTP) Authentication Scheme Registrations][12]
  * [RFC 7237: Initial Hypertext Transfer Protocol (HTTP) Method Registrations][13]


## HTTP
Hypertext Transfer Protocol (HTTP) is a stateless application-level request/response protocol that uses extensible semantics and self-descriptive message payloads:
* HTTP is a **stateless request/response** protocol
* HTTP relies upon the Uniform Resource Identifier (URI) standard [RFC3986] to indicate the target resource
* Most HTTP communication consists of a retrieval request (`GET`) for a
   representation of some resource identified by a URI

## Client Request
A **client** sends an HTTP request to a server in the form of a **request message**, beginning with a request-line that includes: 
* a method, URI, and protocol version,
   
followed by header fields containing:
* request modifiers,
* client information, 
* representation metadata, 
* an empty line to indicate the end of the header section, and  
* a message body containing the payload body.

Example client request:
```
GET /hello.txt HTTP/1.1
User-Agent: curl/7.16.3 libcurl/7.16.3 OpenSSL/0.9.7l zlib/1.2.3
Host: www.example.com
Accept-Language: en, mi
```

## Server Response
A **server** responds to a client's request by sending one or more HTTP **response messages**, each beginning with a status line that includes:
* the protocol version, 
* a success or error code, and textual reason phrase, 

possibly followed by header fields containing:
* server information,
* resource metadata, and
* representation metadata, 
* an empty line to indicate the end of the header section, and 
* a message body containing the payload body.

Example server response:
```
HTTP/1.1 200 OK
Date: Mon, 27 Jul 2009 12:28:53 GMT
Server: Apache
Last-Modified: Wed, 22 Jul 2009 19:15:56 GMT
ETag: "34aa387-d-1568eb00"
Accept-Ranges: bytes
Content-Length: 51
Vary: Accept-Encoding
Content-Type: text/plain

Hello World! My payload includes a trailing CRLF.
```

## Uniform Resource Identifiers
[Uniform Resource Identifiers (URIs)][4] are used throughout HTTP as the means for identifying resources.

An `http` URI scheme:
```
"http:" "//" authority path-abempty [ "?" query ] [ "#" fragment ]
```

## Request methods 
HTTP **request methods** are defined in [RFC 7231][7].

| Method  | Description                                     |
| ------- | ----------------------------------------------- |
| GET     | Transfer a current representation of the target resource |
| HEAD    | Same as GET, but only transfer the status line and header section |
| POST    | Perform resource-specific processing on the request payload |
| PUT     | Replace all current representations of target resource with request payload |
| DELETE  | Remove all current representations of the target resource |
| CONNECT | Establish a tunnel to the server identified by the target resource |
| OPTIONS | Describe the communication options for the target resource |
| TRACE   | Perform a message loop-back test along the path to the target resource |

In `GET` requests, parameters are encoded in the URL:
```
http://www.example.com/index.php?name=alice&id=1
```
In `POST` requests, parameters are transmitted in the message body:
```
POST /index.php HTTP/1.1
Host: www.example.com
Pragma: no-cache
Cache-control: no-cache
User-Agent: curl/7.16.3 libcurl/7.16.3 OpenSSL/0.9.7l zlib/1.2.3
Content-Type: application/x-www-form-urlencoded
Content-Lenght: 15 

name=alice&id=1
```

## Headers

### Referer Header
The "Referer" [sic] header field allows the user agent to specify a URI reference for the resource from which the target URI was obtained (i.e., the "referrer", though the field name is misspelled).

Example:
```
Referer: http://www.example.org/hypertext/Overview.html
```
If the target URI was obtained from a source that does not have its own URI (e.g., input from the user keyboard, or an entry within the user's bookmarks/favorites), the user agent must either exclude the Referer field or send it with a value of "about:blank".

## Cookies
HTTP is a stateless protocol. [RFC 6265: HTTP State Management Mechanism][14] defines cookies. 

Using the `Set-Cookie` header field, an HTTP server can pass name/value pairs and associated metadata (called cookies) to a user agent. 

When the user agent makes subsequent requests to the server, the user agent uses the metadata and other information to determine whether to  return the name/value pairs in the Cookie header.

For historical reasons, cookies contain a number of security and privacy infelicities.  For example, a server can indicate that a given cookie is intended for "secure" connections, but the `Secure` attribute does not provide integrity in the presence of an active network attacker.

### `Set-Cookie` Header
The `Set-Cookie` HTTP response header is used to send cookies from the
   server to the user agent.

`Set-Cookie` response header contains the header name `Set-Cookie` followed by a `:` and a cookie. Each cookie begins with a **name-value pair**, followed by zero or more **attribute-value pairs**.

### `Secure` Attribute
The `Secure` attribute limits the scope of the cookie to "secure" channels (where "secure" is defined by the user agent).  When a cookie has the Secure attribute, the user agent will include the cookie in an HTTP request only if the request is transmitted over a secure channel (typically HTTP over Transport Layer Security (TLS).

### `HttpOnly` Attribute
The `HttpOnly` attribute limits the scope of the cookie to HTTP requests. In particular, the attribute instructs the user agent to omit the cookie when providing access to cookies via "non-HTTP" APIs (such as a web browser API that exposes cookies to scripts).

### `Cookie` Header
The user agent includes stored cookies in the `Cookie` HTTP request header. The header field in the HTTP request can contain at most one `Cookie` header.

If the user agent attaches a `Cookie` header field to an HTTP request, it must send the cookie-string as the value of the header field.

### Example 1
The server can alter the default scope of the cookie using the `Path` and `Domain` attributes. For example, the server can instruct the user agent to return the cookie to every path and every subdomain of `example.com`.

Server &#8594; User Agent:
```
Set-Cookie: SID=31d4d96e407aad42; Path=/; Domain=example.com
```

User Agent &#8594; Server:
```
Cookie: SID=31d4d96e407aad42
```

### Example 2
The server can store multiple cookies at the user agent.  For example, the server can store a session identifier as well as the user's preferred language by returning two `Set-Cookie` header fields.

Server &#8594; User Agent:
```
Set-Cookie: SID=31d4d96e407aad42; Path=/; Secure; HttpOnly
Set-Cookie: lang=en-US; Path=/; Domain=example.com
```

User Agent &#8594; Server:
```
Cookie: SID=31d4d96e407aad42; lang=en-US
```

Notice that the `Cookie` header above contains two cookies, one named `SID` and one named `lang`.

### Example 3
If the server wishes the user agent to persist the cookie over multiple sessions (e.g., user agent restarts), the server can specify an expiration date in the `Expires` attribute.

Server &#8594; User Agent:
```
Set-Cookie: lang=en-US; Expires=Wed, 09 Jun 2021 10:18:14 GMT
```

User Agent &#8594; Server:
```
Cookie: SID=31d4d96e407aad42; lang=en-US
```

### Example 4
To remove a cookie, the server returns a `Set-Cookie` header with an expiration date in the past.

Server &#8594; User Agent:
```
Set-Cookie: lang=; Expires=Sun, 06 Nov 1994 08:49:37 GMT
```

User Agent &#8594; Server:
```
Cookie: SID=31d4d96e407aad42
```


[1]: (http://www.ietf.org/rfc/rfc1738.txt)
[2]: (http://www.ietf.org/rfc/rfc1808.txt)
[3]: (http://www.ietf.org/rfc/rfc2368.txt)
[4]: (https://datatracker.ietf.org/doc/html/rfc3986)
[5]: (http://www.w3.org/TR/html5/)
[6]: (https://datatracker.ietf.org/doc/html/rfc7230)
[7]: (https://datatracker.ietf.org/doc/html/rfc7231)
[8]: (https://datatracker.ietf.org/doc/html/rfc7232)
[9]: (https://datatracker.ietf.org/doc/html/rfc7233)
[10]: (https://datatracker.ietf.org/doc/html/rfc7234)
[11]: (https://datatracker.ietf.org/doc/html/rfc7235)
[12]: (https://datatracker.ietf.org/doc/html/rfc7236)
[13]: (https://datatracker.ietf.org/doc/html/rfc7237)
[14]: (https://datatracker.ietf.org/doc/html/rfc6265)



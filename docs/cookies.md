# Cookies

## Introduction
For a long time, cookies were the only option to persistently store data on the client, and it is still the prevailing solution.

Cookies are frequently used for **user identification**, e.g., at consecutive visits of a web application. Attacks based on cookie manipulation are called **cookie poisoning**

### Cookie Types
Cookies can be persistent or non-persistent. **Persistent** cookies are stored on client's hard drive as long as their date is validated. **Non-persistent** cookies are stored in RAM and are deleted when the web browser is closed.

Moreover, cookies can be `secure` or `HttpOnly`. `secure` cookies are transmitted only via an HTTPS connection. `HttpOnly` cookies are transmitted via HTTP or HTTPS, but cannot be accessed by JavaScript.

### Storage and Manipulation
All web browsers store cookies in known file system locations and in known formats. An attacker can easily manipulate this data before it is used by a web application. 

It is also possible to manipulate cookies **on the fly**, for example, using a tool like the ZAProxy.

Unlike with URL parameters, the attacker can also manipulate the date when the cookie expires, i.e., she can manipulate the cookie's lifetime.

In general, cookies can give you access to things like sessions, profiles, etc.

### Example
Assume the web application under `https://www.weather.xyz` offers detailed weather information against payment. 

`https://www.weather.xyz` uses cookies that contain `userID` to identify users. The cookie is valid for the user's subscription period, e.g., a month.

Information stored in the cookie is processed by `https://www.weather.xyz` without additional authentication. 

This allows following 3 attacks:
* Attack 1: malicious user manipulates `userID` to access `https://www.weather.xyz` as another user
* Attack 2: malicious user extends their subscription by manipulating the cookie expiration date (by changing the Unix-timestamp in the cookie)
* Attack 3: cookies often store the number of failed login attempts. When they reach a specific threshold, say 5, `https://www.weather.xyz` deactivates that user account for 10 minutes to protect against brute force attacks. The attacker bypasses this defense by setting the value of failed login attempts to 0 after each login attempt. Since this can be easily automated, brute force attacks become trivial

## Detection

## Defense

## Demo






\begin{frame}
    \frametitle{Cookie Parameters: Finding Vulnerabilities}
    \begin{itemize}
        \item For every cookie found during recon phase, you must check whether a parameter manipulation results in an illegal state change of the web application
        \item Manipulation should also include cookie parameters like expiration
        \item To detect cookie-related vulnerabilities more efficiently, \textit{decrease} the expiration date and check whether the web application denies its service
        \item As an alternative -- if you have access to the server-side source code of the web application -- check whether the source code uses the cookie expiration date as input parameter
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Cookie Parameters: Defending Against Attacks}
    \begin{itemize}
        \item In general, cookie poisoning -- attacks that manipulate cookies -- cannot be eliminated
        \item Cookies are stored on the client and a malicious user can always manipulate data on the client (even data of other users)
        \item Two prominent attacks in this context are \textit{cross-site-scripting} (XSS) and \textit{cross-site-request-forgery} (CSRF)
        \item Attacker exploits web browser capabilities to set a cookie for a different domain
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Cookie Parameters: Defending Against Attacks}
    \begin{itemize}
        \item As a general rule, never trust data supplied by the client
        \item For example, store subscription's expiration date or the number of failed login attempts on the server
        \item If storing data on the client is unavoidable, encrypt or hash that data
        \begin{itemize}
            \item Question to the audience: why?
        \end{itemize} 
    \end{itemize}
\end{frame}
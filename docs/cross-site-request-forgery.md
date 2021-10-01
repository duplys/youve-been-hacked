# Cross-Site Request Forgery (CSRF)

## Introduction

## Detection

## Defense

\begin{frame}
    \frametitle{Cross-Site Request Forgery}
    \begin{itemize}
        \item Cross-Site Request Forgery (CSRF) attacks exploit web application's trust in Alice to perform unauthorized actions on her behalf
        \item Eve's goal is to trick Alice into unknowingly submitting an HTTP/web request to a web application that Alice has privileged access to (but Eve does not)
        \item Unlike Cross-Site Scripting (XSS) which exploits the trust a user has for a particular site, CSRF exploits he trust that a site has in a user's browser
        \item 
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Cross-Site Request Forgery}
    \begin{itemize}
        \item At risk are web applications that perform actions based on input from trusted and authenticated users without requiring the user to authorize the specific action
        \item For instance, a user who is authenticated by a cookie saved in the user's web browser could unknowingly send an HTTP request to a site that trusts the user and thereby cause an unwanted action
    \end{itemize}
\end{frame}

\begin{frame}[fragile]
    \frametitle{Cross-Site Request Forgery}
    Example 1:
    \begin{itemize}
        \item \verb|www.server.xyz/app/index.php?action=logout| is used to log out users
        \item The web application identifies users through a session-ID stored in a cookie
        \item The cookie is automatically sent by the user's web browser
        \item The web application is an online forum where links can be stored in posts
        \item Eve publishes a post with the above link disguised by an \verb|<a href="...">more info here</a>|
        \item Alice clicks on the link and is logged out
    \end{itemize}
\end{frame}

\begin{frame}[fragile]
    \frametitle{Cross-Site Request Forgery}
    Example 2:
    \begin{itemize}
        \item New user is added when \verb|www.server.xyz/app/admin/adduser.php?name=NAME&pass=PWD| is called by an admin
        \item The web application identifies users through a session-ID stored in a cookie
        \item Eve prepares a malicious web page and tricks the admin to open this page
        \item The web page contains \verb|<img src=www.server.xyz/app/admin/adduser.php?name=eve&pass=eve">|
        \item The (loged in via cookie) admin opens the malicious page
        \item Admin's web browser sends a \verb|GET| request to load the image
        \item The URL to create a new user is called instead
        \item The cookie to authenticate the admin is automatically sent by her web browser
    \end{itemize}
\end{frame}

\begin{frame}[fragile]
    \frametitle{Cross-Site Request Forgery}
    Example 3:
    \begin{itemize}
        \item Instead of a \verb|GET| request, the web application uses a form -- and, thus, a \verb|POST| request -- to add new users:
        \begin{verbatim}
            <form method="post" action="http://www.server.xyz/app/admin/adduser.php">
              Name: <input name="username"> <br>
              Passwort: <input name="password"> <br>
              <input type=submit value="Add User">
            </form>
        \end{verbatim}
        \item The data sent to the application is in the \verb|POST| request, i.e., it doesn't show in the URL
        \item Eve prepares a malicious web page and tricks the admin to open this page
        \item The web page contains a form that is auto-submitted upon opening the pages:
        \begin{verbatim}
            <form name="csrf" method="post" action="http://www.server.xyz/app/admin/adduser.php">
              <input type="hidden" name="username" value="eve">
              <input type="hidden" name="password" value="eve">
            </form>
            <script>document.CSRF.submit()</script>
        \end{verbatim}
        \item The (loged in via cookie) admin opens the malicious page
        \item The form is submitted automatically, as if the admin clicked on "Add User"
        \item The cookie to authenticate the admin is automatically sent by her web browser
    \end{itemize}
\end{frame}

\begin{frame}[fragile]
    \frametitle{Cross-Site Request Forgery}
    Example 3 (cont'd):
    \begin{itemize}
        \item Eve prepares a malicious web page and tricks the admin to open this page
        \item The web page contains a form that is auto-submitted upon opening the pages:
        \begin{verbatim}
            <form name="csrf" method="post" action="http://www.server.xyz/app/admin/adduser.php">
              <input type="hidden" name="username" value="eve">
              <input type="hidden" name="password" value="eve">
            </form>
            <script>document.CSRF.submit()</script>
        \end{verbatim}
        \item The (loged in via cookie) admin opens the malicious page
        \item The form is submitted automatically, as if the admin clicked on "Add User"
        \item The cookie to authenticate the admin is automatically sent by her web browser
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Cross-Site Request Forgery}
    \begin{itemize}
        \item Essentially, it is sufficient to trick an authorized user to send a maliciously crafted HTTP request
        \item As a result, CSRF is not limited to web pages
        \item Any document format that supports scripting can be exploited, e.g., HTML e-mails and multimedia files
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Detecting CSRF Vulnerabilities}
    \begin{itemize}
        \item A web application is susceptible to CSRF when it implements actions that can be triggered via a static URL (\texttt{GET} request) or a static \texttt{POST} request (like a static form)
        \item To prevent CSRF attacks, the corresponding URLs or forms (\texttt{POST} requests) contain the so-called \emph{Anti-CSRF-Token}
        \item The \emph{anti-CSRF-token} is a random value that Eve cannot guess
        \item The action associated with the \texttt{GET} or \texttt{POST} request is only performed if the token contains the correct value
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Detecting CSRF Vulnerabilities}
    \begin{itemize}
        \item Thus, you need to check whether all \texttt{GET} and \texttt{POST} requests perform an action that requires authorization contain a CSRF-token  
        \item Check whether these requests contain parameters that change every time the request is Sensitive
        \item If any of these requests is static, i.e., doesn't change over time, the web application is vulnerable to CSRF
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Defending against CSRF}
    \begin{itemize}
        \item You must add a unique, random token for every relevant \texttt{GET} and \texttt{POST} request
        \item It is important that the token has sufficient entropy so that Eve cannot simply send multiple request with highly likely token values
        \item The web application must only perform actions when the correct token is supplied
        \item Then, Eve cannot prepare a valid request because she can't guess the random token value (she could learn the token value using XSS, though) 
        \item For really important actions, you should add an additional password check
    \end{itemize}
\end{frame}
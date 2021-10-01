# Session Hijacking

## Introduction

## Detection

## Defenses

\begin{frame}
    \frametitle{Session Highjacking}
    \begin{itemize}
        \item Numerous attacks can be alleviated by storing state information on the server (instead of client)
        \item In that case, the web application needs a way to identify the user
        \item The notion of a \emph{session} is used to associate state information (stored on the server) to a specific user
        \item The web application assigns each session a unique \emph{session identifier} (session-ID)
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Session Highjacking}
    \begin{itemize}
        \item Session-ID is a number or a combination of numbers and letters that uniquely identifies a session
        \item Session-ID is assigned to the client (user) upon the first HTTP request to the web application
        \item Every subsequent request carries the session-ID so that the web application can identify the client (user) and retrieve the corresponding state information stored on the server
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Session Highjacking}
    \begin{figure}[htb]
        \centering
        \includegraphics[scale=.5]{uml/session-hijacking.png}
    \end{figure}
    \begin{itemize}
        \item Because the session-ID must be stored on the client, it can be manipulated or stolen
        \item An attack exploiting this vulnerability is referred to as \emph{session hijacking}
        \item \Attacker gets hold of the session-ID information to gain unauthorized access to the web application
    \end{itemize}
\end{frame}

\begin{frame}[fragile]
    \frametitle{Session Highjacking}

    \begin{center}
        \begin{verbatim}
            Name=Alice
            userID=1234
            last=2021-08-24
        \end{verbatim}
    \end{center}

    \begin{itemize}
        \item A special case of session hijacking is the so-called \emph{authorization bypass}
        \item Example: web application identifies the client (user) based on a cookie containing the user name, the user ID and the date of the last session
        \item Is there are user with ID 1235?
        \item \Attacker can change \verb|userID| and use the manipulated cookie to take on the identity of another user
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Finding Session Highjacking Vulnerabilities}
    \begin{itemize}
        \item First, identify the session-ID in the web application, e.g., search for parameters whose names contain \texttt{ID}, \texttt{TOKEN}, \texttt{SESSIONID}, \texttt{ID} or similar strings. These parameters can be located in hidden input fields, URL parameters or in cookies.
        \item Once you identified a potential session-ID, change it and check whether you can impersonate another user
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Defending against Session Highjacking}
    \begin{itemize}
        \item Session hijacking requires a valid session-ID. \Attacker has essentially 4 options to get hold of it:
        \begin{itemize}
            \item Cross-site-scripting (XSS): XSS attacks are commonly used to steal cookies (more on this later). Make sure your web application has no XSS vulnerabilities. In addition, you can set \texttt{HTTPOnly} flag to prevent access to cookies from within JavaScript 
            \item Eavesdropping network traffic: if \attacker can read network traffic between the client and the web application, she can extract the session-ID. Ensure that you web application uses TLS (HTTPS).
            \item Searching logfiles: if \attacker has access to the web server (or proxy) logfiles, she can search them for session-IDs that were transmitted via \texttt{GET} requests. Session-ID should therefore be transmitted in cookies or via \texttt{POST} requests.  
            \item Brute-force attack: \attacker can try to guess the format of your session-IDs and try out various (random) combination until she finds a valid session-ID. You should use large random values as session-IDs.
        \end{itemize}
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Defending against Session Highjacking}
    \begin{itemize}
        \item Performing session identification based on the combination of the session-ID and the IP address of the client makes session hijacking more difficult
        \item Instead of the IP address, you can also use e.g., the \texttt{User-Agent} header (i.e., user the client browser "model" as an additional identification factor)
        \item To reduce session hijacking-related risks, you should end the sessions after a specific time and make the corresponding session-IDs invalid
        \item This can be done using a "hard" time-out (e.g., every session ends after 30 minutes) or a "soft" time-out (e.g., session ends after 10 minutes of inactivity)
    \end{itemize}
\end{frame}
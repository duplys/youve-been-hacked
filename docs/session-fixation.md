# Session Fixation

## Introduction

## Detection

## Defenses

\begin{frame}
    \frametitle{Session Fixation}
    \begin{figure}[htb]
        \centering
        \includegraphics[scale=.5]{uml/session-fixation.png}
    \end{figure}
    \begin{itemize}
        \item Instead of stealing or guessing the session-ID, \attacker attempts to fixate other user's session-ID
        \item Session fixation attacks are typically web based and rely on session identifiers being accepted from URL parameters or POST data
        \item Example: \attacker sends Alice an email with a link to \texttt{login.php?session=34fgfash6f4fss3}
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Detecting Session Fixation Vulnerabilities}
    \begin{itemize}
        \item Use the information from the recon phase to identify session-IDs
        \item Call the web application with a session-ID that is already used, i.e., has been set by the web application
        \item Example: if the entry to the web application is \texttt{www.examle.xyz} and the web application, in turn, returns \texttt{www.example.xyz/index.php?session=[valid-session-ID]}, call this URL directly
        \item If you can login and your session-ID is \texttt{[valid-session-ID]}, you've found a session fixation vulnerability
        \item If session-ID is transmitted in a cookie, use ZAProxy to manipulate it   
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Defending against Session Fixation}
    \begin{itemize}
        \item Make sure your web application generates a new, random session-ID each time a user logs in
        \item The same behaviour must be implemented when a new users signs up
        \item Some web applications accept arbitrary session-IDs, i.e., session-IDs that they have not generated
        \item This makes Eve's life easier because she doesn't need to obtain a valid session-ID
        \item Thus, your web application should only accept session-IDs that it actually generated 
        \item Make sure it's impossible to reactivate expired sessions with known "old" session-IDs. If a session expired, a new session must be created with a new session-ID and a new state. Otherwise Eve can mount replay attacks.
        \item You should also leverage the information in HTTP header's \texttt{Referer} field. If the observed sequence of URLs doesn't match the expected sequence, either URL jumping is taking place or two clients (users) are using the same session-ID
    \end{itemize}
\end{frame}
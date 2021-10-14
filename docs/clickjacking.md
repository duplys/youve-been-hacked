# Clickjacking

## Introduction

## Detection

## Defense

\begin{frame}
    \frametitle{Clickjacking}
    \begin{figure}
        \centering
        \includegraphics[scale=.3]{img/clickjacking.png}
    \end{figure}
    \begin{itemize}
        \item \eve tricks \alice into clicking on something different from what \alice perceives and, as a result, 
        \item This way, \eve can trick \alice into performing undesired actions by clicking on concealed links
        \item Clickjacking works because JavaScript allows to load a transparent layer over a web page and have the user's input affect that transparent layer without the user noticing
        \item For clickjacking to work, \alice must visit a page under Eve's control (because JavaScript is needed to create the transparent layer)
    \end{itemize}
    Image source: \href{https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Clickjacking.png/1024px-Clickjacking.png}{Wikimedia}
\end{frame}

\begin{frame}
    \frametitle{Clickjacking}
    Example:
    \begin{itemize}
        \item Alice receives an email with a link to a video about a news item on a page under Eve's control
        \item Eve overlays a product page on Amazon on top of the "play" button of the news video
        \item Alice clicks on "play" to start the video, but actually buys the product from Amazon
        \item Eve can only send a single click, so she relies on the fact that Alice is both logged into Amazon.com and has the 1-click ordering enabled 
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Clickjacking}
    \begin{itemize}
        \item Technically, Eve embeds the target page an an iframe into the web page that will be displayed to Alice
        \item Eve reduces the size of the iframe such that only the target link is included; the rest of the target page is beyond the iframe
        \item Eve can configure the iframe to follow the mouse pointer. So regardless where Alice clicks, she will always clock on the target link
        \item Finally, Eve adjusts the opacity of the \texttt{div} element that spans the iframe such that the iframe becomes invisible to Alice
        \item After tricking Alice into clicking the target link, Eve can bring the visible page into the foreground so that Alice will likely not notice the attack
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Detecting Clickjacking Vulnerabilities}
    \begin{itemize}
        \item Check whether your application contains pages where only authenticated users can perform certain actions
        \item Then, check whether these pages can be embedded in iframes
    \end{itemize}
\end{frame}

\begin{frame}
    \frametitle{Defending against Clickjacking}
    \begin{itemize}
        \item To defeat clickjacking, you must prevent relevant web pages of your web application from being embedded in an iframe
        \item A \emph{framebuster} is a small JS script that prevents the web page from being displayed in a frame (however, HTML5 capabilities -- loading a page in a frame with the \texttt{sandbox} attribute -- allow Eve to bypass any framebusters)
        \item \texttt{X-Frame-Option} header can be used (by the web server) to specify preferred framing policy:
        \begin{itemize}
            \item \texttt{deny} prevents any framing
            \item \texttt{sameorigin} prevents framing by external sites
            \item \texttt{allow-from origin} allows framing only by the specified site
        \end{itemize}
        \item The \texttt{frame-ancestors} directive of the Content Security Policy can be used to allow or disallow embedding a web page in an iframe
    \end{itemize}
\end{frame}
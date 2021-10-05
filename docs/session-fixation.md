# Session Fixation

## Introduction
Instead of stealing or guessing the session-ID, Eve attempts to **fixate** other user's session-ID. Session fixation attacks are typically web based and rely on session identifiers being accepted from URL parameters or `POST` data.

![Session fixation](img/session-fixation/session-fixation.png "Session fixation")

Example: Eve sends Alice an email with a link to `login.php?session=34fgfash6f4fss3`

## Detection
Use the information from the recon phase to identify session-IDs. Call the web application with a session-ID that is already used, i.e., has been set by the web application.

For example, if the entry to the web application is `www.examle.xyz` and the web application, in turn, returns `www.example.xyz/index.php?session=[valid-session-ID]`, call this URL directly. If you can login and your session-ID is `[valid-session-ID]`, you've found a session fixation vulnerability. If session-ID is transmitted in a cookie, use ZAProxy to manipulate it,

## Defense
Make sure your web application generates a **new, random session-ID** each time a user logs in. The same behaviour must be implemented when a new user signs up.

Some web applications accept arbitrary session-IDs, i.e., session-IDs that they have not generated. This makes Eve's life easier because she doesn't need to obtain a valid session-ID. Thus, your web application should only accept session-IDs that it actually generated.

Make sure it's impossible to reactivate expired sessions with known old session-IDs. If a session expired, a new session must be created with a new session-ID and a new state. Otherwise Eve can mount replay attacks.

In addition, you can also use the information in HTTP header's `Referer` field. If the observed sequence of URLs doesn't match the expected sequence, either URL jumping is taking place or two clients (user agents) are using the same session-ID.

## Demo

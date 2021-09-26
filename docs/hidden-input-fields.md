# Hidden Input Fields

## Introduction
[A hidden field allows to include data that cannot be seen or modified by users when a form is submitted. A hidden field often stores what database record needs to be updated when the form is submitted][1]. Here's an example of a hidden input field:

```html
<input name="id" value="1234" type="hidden">        
```

From security perspecitve, hidden input fields can contain state information that is critical for the correct functionality of the web application. Because hidden input fields can be easily manipulated using a proxy like ZAP or web developer tools in the web browser, they represent a potential security vulnerability.

## Detection
`grep` your web applications's source code for hidden input fields and test whether the application's behavior changes when you change the values of these hidden input fields.

A classical anti-pattern &mdash; luckily not so common anymore &mdash; is to use hidden input fields for storing item prices in a web shop. Another example is storing user's status, e.g., whether the user is signed in or just a guest, or whether the user is an an administrator or just a standard user.

## Defense
From the security perspective, the underlying problem with hidden input fields is that the state information is stored **on the client**, where it is easy to manipulate.

The main defense philosophy &mdash; as with numerous other web security threats &mdash; is to **never trust the client**. Any critical information used by your web app must be stored on the server. Values received from the client must always be checked & validated (if possible). 

If values must be stored on the client (e.g., session IDs), they should be encrypted or hashed to make their manipulation harder.

## Demo
[Start Docker containers with `docker-compose up`, start ZAP session, update ZAP, download and import SSL certificate, navigate to the vulnerable web application, and run `install.php` to populate the database][2].

Click on "Administrationsbereich" to get to:

![Administrationsbereich](img/app-beta-admin.png "Administrationsbereich")

While this would be conceiled 

Navigate to `app/beta/index.php` and click on "Backend":

![Beta app to backend](img/beta-app-to-backend.png "Beta app to backend")

That brings you to:

![Beta app to backend](img/beta-app-at-backend.png "Beta app to backend")

Next, log in as user `test` with password `test`:

![Beta app login as test/test](img/beta-app-logging-in-as-test-test.png "Beta app login as test/test")

Once you're loged in, you'll see this form for creating a new text entry:

![Beta app login as test/test](img/beta-app-backend-text.png "Beta app login as test/test")

If you view the source code of that web page (while logged in), you'll discover that it contains hidden input fields `BenutzerID` and `BenutzerStatus`:

![Hidden input fields in the beta app](img/hidden-input-fields-source-code.png "Hidden input fields in the beta app")

You are currently logged in as user `test`. Set break on all requests and responses in ZAP (click on the green circle, it turns red):

![ZAP brake for requests and responses](img/zap-break-requests-responses.png "ZAP brake for requests and responses")

Enter title and text and submit the text entry:

![Beta app submit text entry](img/app-beta-submit-text-entry.png "Beta app submit text entry")

Switch to ZAP. Your HTTP request to the vulnerable web application is on hold and you can manipulate the values of the hidden input fields:

![ZAP manipulate hidden input field](img/zap-manipulate-hidden-input-field.png "ZAP manipulate hidden input field")

Change the value of `BenutzerID` to `1` and click on "Submit and continue to next breakpoint":

![ZAP manipulate BenutzerID](img/zap-benutzerid-manipulation.png "ZAP manipulate BenutzerID")

Go back to the vulnerable web application. As you can see, you are now logged in as `autor`, the user with ID 1:

![Logged in as autor](img/app-beta-logged-in-as-autor.png "Logged in as autor")

This hidden input field vulnerability effectively allows you to post content under a different user name.

[1]: https://www.w3schools.com/tags/att_input_type_hidden.asp
[2]: start-docker-containers.md
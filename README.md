# You've Been Hacked &mdash; An Interactive Course on Web Security
An interactive web security course based on Carsten Eiler's book "You've Been Hacked", Rheinwerk Computing, 1st edition (ISBN 978-3-8362-4460-2).

## Synopsys
This repository contains Dockerfiles, setup instructions, some code and write-ups for carrying out the experiments described in Carsten Eiler's book "You've Been Hacked" on security vulnerabilities in web applications. It also contains slides summarizing each chapter that can be used for teaching.

## Creating Docker Containers
Running the demo web application in a Docker container is the easiest way to get started. The `Docker` directory in this repository contains the `Dockerfile` needed to build the Docker image with the vulnerable demo web application. You can build the image manually using `Makefile` or simply run `docker-compose`.

## Running Dockers Containers
### Using `docker-compose`
This is the easiest way to run the containers. Simply issue `docker-compose up` and `docker-compose` will take care of all configuration details:

```shell
$ docker-compose up
Creating network "docker_hacknet" with the default driver
Creating docker_zap_1     ... done
Creating docker_vulnapp_1 ... done
Attaching to docker_zap_1, docker_vulnapp_1
vulnapp_1  |  * Starting web server apache2
vulnapp_1  | AH00558: apache2: Could not reliably determine the server's fully qualified domain name, using 172.18.0.3. Set the 'ServerName' directive globally to suppress this message
zap_1      | Using ZAP command line options: -host 0.0.0.0 -port 8090
vulnapp_1  |  * 
vulnapp_1  |  * Starting MySQL database server mysqld
vulnapp_1  |    ...done.
vulnapp_1  |  * Checking for tables which need an upgrade, are corrupt or were 
vulnapp_1  | not closed cleanly.

```


### Using `docker container run`
If you prefer to run the containers manually, you first need to create a new Docker network:
```shell
$ docker network create -d bridge hack-network
```
Next, you need to start the container with the vulnerable app:

```shell
$ docker container run --rm -it -p 8888:80 --network="hack-network" -v $(PWD)/tmp:/opt/tmp youve-been-hacked
```
Note that for the above command, you will need an empty directory `tmp` in the directory where you run this command.

and the container with ZAProxy:

```shell
$ docker container run -u zap -p 8080:8080 -p 8090:8090 --network="hack-network" -i owasp/zap2docker-stable zap-webswing.sh
```

If everything went well, you should see two containers with `docker container ps`:

```shell
$  docker container ps -a                                                                   
CONTAINER ID        IMAGE                     COMMAND             CREATED              STATUS                      PORTS                                            NAMES
eb264e004d2a        owasp/zap2docker-stable   "zap-webswing.sh"   58 seconds ago       Up 57 seconds (unhealthy)   0.0.0.0:8080->8080/tcp, 0.0.0.0:8090->8090/tcp   kind_cohen
9ea057ec1cea        youve-been-hacked         "/bin/bash"         About a minute ago   Up About a minute           0.0.0.0:8888->80/tcp                             exciting_maxwell
$
```

## Setting up ZAProxy
As described [in this post](https://www.zaproxy.org/docs/docker/webswing/), you will need to activate ZAProxy (ZAP), configure your browser to [proxy via ZAP](https://www.zaproxy.org/docs/desktop/start/proxies/) and [import the public ZAP Root certificate](https://www.zaproxy.org/docs/desktop/ui/dialogs/options/dynsslcert/#install) so that it is trusted to sign websites. You can create a separate browser profile for proxying through ZAP (see [this page for Firefox](https://support.mozilla.org/en-US/kb/profile-manager-create-remove-switch-firefox-profiles)).

First, fire up your web browser and visit `http://127.0.0.1:8080/zap/`. You'll see how the ZAP Web UI starts. Start a ZAP session (you can choose "I do not want to persist this session at this moment in time"). Select "Update All" in the "Manage Add-ons" window.

![zap](slides/img/zap-new-session-find-ip-address-and-port-for-proxy.png)

Next, go to "Tools" -> "Options" -> "Dynamic SSL Certificates" -> "Save" and save the ZAP certificate on your host and import it into your browser. Read off the IP address and port number at the bottom of ZAP's window and configure your web browser to use that IP/port as proxy. 

You're now ready to play with the vulnerable demo app: go to `http://host.docker.internal:8888/daten/kapitel1.html` and you should see the web application. You must use `host.docker.internal` instead of `127.0.0.1` when proxying through ZAP. Otherwise, your HTTP request would be resolved to the localhost of the Docker container where ZAP is running and you would get a "connection refused" warning and a Bad Gateway response.

## References
* https://security.secure.force.com/security/tools/webapp/zapbrowsersetup
* https://security.secure.force.com/security/tools/webapp/zapclientsetup
* https://docs.docker.com/docker-for-mac/networking/
* https://stackoverflow.com/questions/24319662/from-inside-of-a-docker-container-how-do-i-connect-to-the-localhost-of-the-mach
* https://www.zaproxy.org/docs/desktop/ui/dialogs/options/dynsslcert/#install
* https://www.zaproxy.org/docs/desktop/ui/dialogs/options/dynsslcert/
* https://www.zaproxy.org/docs/docker/webswing/
* https://www.zaproxy.org/docs/docker/about/
* https://medium.com/volosoft/running-penetration-tests-for-your-website-as-a-simple-developer-with-owasp-zap-493d6a7e182b
* https://www.rheinwerk-verlag.de/youve-been-hacked-alles-ueber-exploits-gegen-webanwendungen/
* https://tex.stackexchange.com/questions/15/spell-checking-latex-documents
* [HTTP Cookies](https://en.wikipedia.org/wiki/HTTP_cookie)
* [Session Fixation](https://en.wikipedia.org/wiki/Session_fixation)
* [Clickjacking](https://en.wikipedia.org/wiki/Clickjacking)
* [Cross-Site Request Forgery](https://en.wikipedia.org/wiki/Cross-site_request_forgery)

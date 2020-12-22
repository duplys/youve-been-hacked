# You've Been Hacked &mdash; An Interactive Course on Web Security
An interactive web security course based on Carsten Eiler's book "You've Been Hacked", Rheinwerk Computing, 1st edition (ISBN 978-3-8362-4460-2).

## Synopsys
This repository contains Dockerfiles, setup instructions, some code and write-ups for carrying out the experiments described in Carsten Eiler's book "You've Been Hacked" on security vulnerabilities in web applications. It also contains slides summarizing each chapter that can be used for teaching.

## Creating Docker Containers
tbd

## Starting the Containers
First, we need to create a new Docker network:
```shell
$ docker network create -d bridge hack-network
```

```shell
$ docker container run --rm -it -p 8888:80 --network="hack-network" -v $(PWD)/tmp:/opt/tmp youve-been-hacked
```
Note that for the above command, you will need an empty directory `tmp` in the directory where you run this command.

and 

```shell
$ docker run -u zap -p 8080:8080 -p 8090:8090 --network="hack-network" -i owasp/zap2docker-stable zap-webswing.sh
```

Open a new terminal window and check whether everything worked out:

```shell
$  docker container ps -a                                                                   
CONTAINER ID        IMAGE                     COMMAND             CREATED              STATUS                      PORTS                                            NAMES
eb264e004d2a        owasp/zap2docker-stable   "zap-webswing.sh"   58 seconds ago       Up 57 seconds (unhealthy)   0.0.0.0:8080->8080/tcp, 0.0.0.0:8090->8090/tcp   kind_cohen
9ea057ec1cea        youve-been-hacked         "/bin/bash"         About a minute ago   Up About a minute           0.0.0.0:8888->80/tcp                             exciting_maxwell
$
```

If all went well, you should see the two containers there.

## Setting up ZAProxy

Next, go your web browser and visit `http://127.0.0.1:8080/zap/` (as [described here](https://www.zaproxy.org/docs/docker/webswing/))

Next, you'll need the IP address of the Docker container running the vulnerable application. To extract this, do:

```shell
$ % docker container inspect exciting_maxwell
[
    {
        "Id": "9ea057ec1cea78eb1ab3abe4ba9f1cadb42ab751bf7bc1c9b0f277a286eeeddf",
        "Created": "2020-11-20T19:35:08.9811261Z",

-- snip --

            "Networks": {
                "hack-network": {
                    "IPAMConfig": null,
                    "Links": null,
                    "Aliases": [
                        "9ea057ec1cea"
                    ],
                    "NetworkID": "84aadd9f11e09968530e5093bdb8c95df7f30aa7efa7526ab315e30558126e03",
                    "EndpointID": "adc8e373fe433540b208498a592d3598d79cb46ab860d1a302301ae9378136b2",
                    "Gateway": "172.19.0.1",
                    "IPAddress": "172.19.0.2",
                    "IPPrefixLen": 16,
                    "IPv6Gateway": "",

-- snip --
```

You'll need to import the dynamic SSL certificate into Firefox. (go to ZAP --> Option -> Dynamic SSL Certificates and download one...). 

Now, in your Firefox browser you need to enter the following URL: `http://host.docker.internal:8888/daten/kapitel1.html`. The reason for this is that if you use `127.0.0.1` together with the ZAP proxy, once that HTTP request arrives at the proxy, the proxy running in a docker container tries to resolve it and hits itself. So you get a "connection refused" warning and a Bad Gateway HTML response.


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

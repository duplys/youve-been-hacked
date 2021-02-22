run:
	docker container run --rm -it -p 8888:80 --network="hack-network" -v $(PWD)/tmp:/opt/tmp youve-been-hacked

build:
	docker build -t youve-been-hacked .

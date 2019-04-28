app ?=
TAG ?= latest
DIR ?= ${PWD}
DOCKERUSER ?= root
DEVDIR ?= $(shell pwd | xargs dirname)


up:
	docker-compose up

build_image:
	docker build .
.PHONY: test

help: ## Shows this help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' -e 's/:.*#/ #/'

uv: ## Update library version (Usage.: make uv version=1.2.3)
	sed -i 's/"version": ".*"/"version": "${version}"/g' composer.json \
	&& composer update --lock \
	&& git commit -am "bump version to ${version}"
	&& git tag "${version}"

# --  CONFIGURE THIS!  --------------------------

SHELL		:= /bin/bash
USER		:= kriss
GROUP		:= www-data
RIGHTS		:= 775

# --  TARGETS  ----------------------------------
PATH_SELF	:= $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

all: git-update set-rights create-docs git-show-status

git-show-status:
	@git status

git-show-changes:
	@git diff

git-update:
	@git fetch
	@git pull

go-create-docs:
	@php go.php create doc

go-test-self:
	@php go.php test self

go-test-syntax:
	@php go.php test syntax

go-test-units:
	@php go.php test units

go-help:
	@php go.php help

set-rights:
	@sudo chown -R ${USER} .
	@sudo chgrp -R ${GROUP} .
	@chmod -R ${RIGHTS} .


# generate .htaccess file to move to your project, enabling autoloading
generate-htaccess:
	@echo 'php_value auto_prepend_file "${PATH_SELF}autoload.php"' > .htaccess
	@echo ".htaccess generated."
	@echo "Now you can move this file to your project to enable autoloading."


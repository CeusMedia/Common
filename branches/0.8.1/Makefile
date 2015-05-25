USER	:= kriss
GROUP	:= www-data
RIGHTS	:= 775

all: update set-rights create-docs show-changes

show-changes:
	@svn diff .

update:
	svn up .

go-create-changelog:
	@php go.php changelog

go-create-docs:
	@php go.php create doc creator

go-test-self:
	@php go.php test self

go-test-syntax:
	@php go.php test syntax

go-test-units:
	@php go.php test units

go-help:
	@php go.php help

#test-units:
#	@phpunit Test

#doc-api:
	
set-rights:
	@sudo chown -R ${USER} .
	@sudo chgrp -R ${GROUP} .
	@chmod -R ${RIGHTS} .

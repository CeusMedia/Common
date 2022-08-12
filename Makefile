# --  CONFIGURE THIS!  ----------------------------------------------------
SHELL		:= /bin/bash
USER		:= kriss
GROUP		:= www-data
PATH_SELF	:= $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
PERM_FOLDER := 755
PERM_FILE   := 644

help:
	@cat tool/Go/make.txt

# --  COMPOSER  -----------------------------------------------------------
composer-install-dev:
	@composer install --dev

composer-install-nodev:
	@composer install --no-dev

composer-update-dev:
	@composer update --dev

composer-update-nodev:
	@composer update --no--dev

# --  DEV: TESTS  ---------------------------------------------------------
dev-test-self:
	@php tool/go.php test self

dev-test-syntax:
	@php tool/go.php test syntax
#	@find src -type f -print0 | xargs -0 -n1 xargs php -l
#	@php test/syntax.php

dev-test-unit: composer-install-dev
#	@php tool/go.php test units
	@vendor/bin/phpunit --configuration tool/config/phpunit9.xml --testsuite units

# --  DEV: Docs  ---------------------------------------------------------
dev-create-docs: composer-install-dev
#	@php tool/go.php create doc
	@php vendor/ceus-media/doc-creator/doc-creator.php --config-file=tool/config/doc-creator.xml

# --  DEV: QUALITY--------------------------------------------------------
dev-phpstan:
	@vendor/bin/phpstan analyse --configuration tool/config/phpstan.neon --xdebug || true

dev-phpstan-clear-cache:
	@vendor/bin/phpstan clear-cache

dev-phpstan-save-baseline:
	@vendor/bin/phpstan analyse --configuration tool/config/phpstan.neon --generate-baseline tool/config/phpstan-baseline.neon || true

dev-rector-rules-apply:
	@vendor/bin/rector process --config=tool/config/rector-rules --no-diffs

dev-rector-php7.3-apply:
	@vendor/bin/rector process --config=tool/config/rector-php73 --no-diffs

dev-rector-rules-dry:
	@vendor/bin/rector process --config=tool/config/rector-rules.php --dry-run

dev-rector-php7.3-dry:
	@vendor/bin/rector process --config=tool/config/rector-php73 --dry-run

# --  GIT  ----------------------------------------------------------------
git-show-status:
	@git status

git-show-changes:
	@git diff

git-update:
	@git fetch
	@git pull


# --  TARGETS  ------------------------------------------------------------
go-help:
	@php tool/go.php help

set-rights:
	@sudo chown -R ${USER} .
	@sudo chgrp -R ${GROUP} .
	@find . -type d -not -path "./vendor/*" -print0 | xargs -0 xargs sudo chmod ${PERM_FOLDER} >/dev/null 2>&1 || true
	@find . -type f -not -path "./vendor/*" -print0 | xargs -0 xargs sudo chmod ${PERM_FILE} >/dev/null 2>&1 || true

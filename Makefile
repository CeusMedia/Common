# --  CONFIGURE THIS!  ----------------------------------------------------
SHELL		:= /bin/bash
USER		:= kriss
GROUP		:= www-data
PATH_SELF	:= $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

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

dev-test-unit: composer-install-dev
#	@php tool/go.php test units
	@vendor/bin/phpunit test

# --  DEV: Docs  ---------------------------------------------------------
dev-create-docs: composer-install-dev
#	@php tool/go.php create doc
	@php vendor/ceus-media/doc-creator/doc-creator.php --config-file=doc-creator.xml

# --  DEV: QUALITY--------------------------------------------------------
dev-phpstan:
	@vendor/bin/phpstan analyse --configuration phpstan.neon --xdebug || true

dev-phpstan-save-baseline:
	@vendor/bin/phpstan analyse --configuration phpstan.neon --generate-baseline phpstan-baseline.neon || true



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
	@find . -type d -not -path "./vendor/*" -print0 | xargs -0 xargs sudo chmod 755 >/dev/null 2>&1 || true
	@find . -type f -not -path "./vendor/*" -print0 | xargs -0 xargs sudo chmod 644 >/dev/null 2>&1 || true

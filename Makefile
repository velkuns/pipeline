.PHONY: validate install update deps phpcs phpcbf php82compatibility php83compatibility phpstan analyze tests testdox ci clean patchcoverage build

define header =
    @if [ -t 1 ]; then printf "\n\e[37m\e[100m  \e[104m $(1) \e[0m\n"; else printf "\n### $(1)\n"; fi
endef

#~ Composer dependency
validate:
	$(call header,Composer Validation)
	@composer validate

install:
	$(call header,Composer Install)
	@composer global require maglnet/composer-require-checker
	@composer install

update:
	$(call header,Composer Update)
	@composer update
	@composer bump --dev-only

composer.lock: install

#~ Vendor binaries dependencies
vendor/bin/phpcbf:
vendor/bin/phpcs:
vendor/bin/phpstan:
vendor/bin/phpunit:

#~ Report directories dependencies
build/reports/phpunit:
	@mkdir -p build/reports/phpunit

build/reports/phpcs:
	@mkdir -p build/reports/cs

build/reports/phpstan:
	@mkdir -p build/reports/phpstan

#~ main commands
deps: composer.json
	$(call header,Checking Dependencies)
	@XDEBUG_MODE=off composer-require-checker check

phpcs: vendor/bin/phpcs build/reports/phpcs
	$(call header,Checking Code Style)
	@./vendor/bin/php-cs-fixer check

phpcbf: vendor/bin/phpcbf
	$(call header,Fixing Code Style)
	@./vendor/bin/php-cs-fixer fix -v

php82compatibility: vendor/bin/phpstan build/reports/phpstan
	$(call header,Checking PHP 8.2 compatibility)
	@XDEBUG_MODE=off ./vendor/bin/phpstan analyse --configuration=./ci/php82-compatibility.neon --error-format=checkstyle > ./build/reports/phpstan/php82-compatibility.xml

php83compatibility: vendor/bin/phpstan build/reports/phpstan
	$(call header,Checking PHP 8.3 compatibility)
	@XDEBUG_MODE=off ./vendor/bin/phpstan analyse --configuration=./ci/php83-compatibility.neon --error-format=checkstyle > ./build/reports/phpstan/php83-compatibility.xml

phpstan: vendor/bin/phpstan build/reports/phpstan
	$(call header,Running Static Analyze)
	@XDEBUG_MODE=off ./vendor/bin/phpstan analyse --error-format=checkstyle > ./build/reports/phpstan/phpstan.xml

analyze: vendor/bin/phpstan build/reports/phpstan
	$(call header,Running Static Analyze - Pretty tty format)
	@XDEBUG_MODE=off ./vendor/bin/phpstan analyse --error-format=table

tests: vendor/bin/phpunit build/reports/phpunit #ci
	$(call header,Running Unit Tests)
	@XDEBUG_MODE=coverage php -dzend_extension=xdebug.so ./vendor/bin/phpunit --testsuite=unit --coverage-clover=./build/reports/phpunit/clover.xml --log-junit=./build/reports/phpunit/unit.xml --coverage-php=./build/reports/phpunit/unit.cov --coverage-html=./build/reports/coverage/ --fail-on-warning

integration: vendor/bin/phpunit build/reports/phpunit #manual
	$(call header,Running Integration Tests)
	@XDEBUG_MODE=coverage php -dzend_extension=xdebug.so ./vendor/bin/phpunit --testsuite=integration --fail-on-warning

testdox: vendor/bin/phpunit #manual
	$(call header,Running Unit Tests (Pretty format))
	@XDEBUG_MODE=coverage php -dzend_extension=xdebug.so ./vendor/bin/phpunit --testsuite=unit --fail-on-warning --testdox

patchcoverage: build/reports/phpunit/unit.cov
	$(call header,Computing PR coverage)
	@git diff origin/$(TARGET_BRANCH)...HEAD > /tmp/pr.patch
	@XDEBUG_MODE=off ./vendor/bin/phpcov patch-coverage --path-prefix $(PWD) build/reports/phpunit/unit.cov /tmp/pr.patch

clean:
	$(call header,Cleaning previous build)
	@if [ "$(shell ls -A ./build)" ]; then rm -rf ./build/*; fi; echo " done"

ci: clean validate deps install phpcs tests integration php82compatibility php83compatibility analyze

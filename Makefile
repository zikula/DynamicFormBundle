# @see https://www.strangebuzz.com/en/snippets/the-perfect-makefile-for-symfony
.PHONY : cp test build deploy eol verify

## —— GENERAL —————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— CODING —————————————————————————————————————————————————
cs: ## Check coding standards with php-cs-fixer (config = .php-cs-fixer.dist.php)
	./vendor/bin/php-cs-fixer fix --dry-run

csf: ## Check coding standards with php-cs-fixer (config = .php-cs-fixer.dist.php)
	./vendor/bin/php-cs-fixer fix

stan: ## PHPStan Static Code analysis
	./vendor/bin/phpstan analyse

## —— TEST —————————————————————————————————————————————————
test: cs stan phpunit ## check coding standards and run tests

phpunit: ## Run PHPUnit Test
	./vendor/bin/phpunit -c phpunit.xml.dist --coverage-text

phpunit-cov: ## Run PHPUnit Test with coverage. Output to html in /temp-coverage
	XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-clover coverage.xml
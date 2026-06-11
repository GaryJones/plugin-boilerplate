# Plugin scripts

This directory contains helper scripts used during development and CI:

- `wp-env-phpunit`: runs PHPUnit inside the wp-env tests container, deriving the plugin mount path from the clone directory name. Pass `--multisite` to run against a multisite installation.
- `xml-lint`: validates `.phpcs.xml.dist` and `phpunit.xml.dist` against their bundled schemas, and checks the formatting of the PHPCS ruleset. Requires `composer install` first.

PHP syntax linting is handled by [PHP Parallel Lint](https://github.com/php-parallel-lint/PHP-Parallel-Lint) via `composer lint`.

## Running integration tests

Integration tests run inside [`@wordpress/env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) (Docker), configured by `.wp-env.json`.

```sh
npm -g install @wordpress/env
wp-env start
composer test:integration
composer test:integration-ms
```

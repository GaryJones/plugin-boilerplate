# Plugin scripts

This directory contains helper scripts used during development and CI:

- `php-lint`: lints all PHP files for syntax errors.
- `xml-lint`: lints `.xml` and `.xml.dist` config files against their schemas.

## Running integration tests

Integration tests run inside [`@wordpress/env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) (Docker), configured by `.wp-env.json`.

```sh
npm -g install @wordpress/env
wp-env start
composer integration
```

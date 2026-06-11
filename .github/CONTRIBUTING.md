# Contributing to Plugin Boilerplate

Community-made patches, localisations, bug reports and contributions are very welcome, and help make this plugin better.

By participating in this project, you agree to abide by the [Code of Conduct](../CODE_OF_CONDUCT.md).

## Reporting Issues

- Check the [issue tracker](https://github.com/GaryJones/plugin-slug/issues) to see whether your issue has already been reported.
- Clearly describe the issue, including steps to reproduce any bug.
- Include the plugin, WordPress and PHP versions you're using.

## Making Changes

- Fork the repository on GitHub, and create a topic branch from `main`.
- Set up your local environment as described in the [Development section of the README](../README.md#development).
- Make your changes, adding or updating unit and integration tests where appropriate.
- When committing, reference the related issue (if there is one) and include a note about the fix.
- Push the changes to your fork, and submit a pull request to the `main` branch of this repository.

### Checking Your Changes

After `composer install`, run these from the project root:

- Lint PHP and XML files: `composer lint`
- Check coding standards: `composer cs` (use `composer cs-fix` to fix violations automatically)
- Run unit tests: `composer test:unit`
- Run integration tests (requires `wp-env` to be running): `composer test:integration`
- Run mutation tests: `composer infection`

## Code Documentation

Please make sure that every function, class and method is well documented, and that the documentation follows the [WordPress PHP documentation standards](https://developer.wordpress.org/coding-standards/inline-documentation-standards/php/).

At this point, you're waiting on us to review your pull request. We'll review all pull requests, and make suggestions and changes if necessary. Please keep each pull request focused on a single fix or feature.

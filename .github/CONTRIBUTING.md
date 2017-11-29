# Contribute To ...

Community-made patches, localisations, bug reports and contributions are very welcome and help make ... the best plugin for ....

When contributing please ensure you follow the guidelines below so that we can keep on top of things.

## Getting Started

- Submit a ticket for your issue, assuming one does not already exist.
- Raise it on our [Issue Tracker](https://github.com/GaryJones/.../issues).
- Clearly describe the issue including steps to reproduce the bug.
- Make sure you fill in the earliest version that you know has the issue as well as the version of WordPress you're using.

## Making Changes

- Fork the repository on GitHub.
- Make the changes to your forked repository.
- Add unit / integration tests where necessary.
- Ensure you stick to the [WordPress Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards) and have properly documented any new functions.
- When committing, reference your issue (if present) and include a note about the fix.
- Push the changes to your fork and submit a pull request to the 'develop' branch of the ... repository.

### Checking You Changes

- Run unit tests: `phpunit`
- Run integration tests: `phpunit --testsuite integration`
- Run mutation tests: `infection` (see `logs/infection.log`)
- Run code standards: `composer phpcs`

## Code Documentation

- Please make sure that every method is documented well and the documentation follows the standards.

At this point you're waiting on us to merge your pull request. We'll review all pull requests, and make suggestions and changes if necessary.

# Additional Resources
- [General GitHub Documentation](http://help.github.com/)
- [GitHub Pull Request documentation](http://help.github.com/send-pull-requests/)

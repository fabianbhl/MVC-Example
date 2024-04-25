# Changelog

All notable changes and additions will be documented in this file.

## 2024-04-25

### Added
- Implemented comprehensive error handling for 404 (notFound), general errors, uncaught exceptions, and system shutdown scenarios.
- Integrated .env capabilities to configure and control error handling behaviors tailored for development and production environments.
- Added a new route 500_test to showcase error output for demonstration and testing purposes.
- Introduced the JsonResponse class to standardize and simplify JSON output across the application.
- Implemented a dedicated bootstrap file responsible for initializing essential application functionalities such as error handling, autoloading, and more, ensuring a cleaner and more organized index.php file.
- Enhanced code documentation by adding comments to classes and methods, aligning with industry standards for improved code readability and maintainability.
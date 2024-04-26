# Model-View-Controller Example

## Overview
This project implements a simple PHP routing system with middleware support. It provides a foundational framework for handling HTTP requests and routing them to the appropriate controllers based on the URI patterns. This setup includes basic controllers, example middleware for authentication, and the core router which manages the dispatch process.

## Features
1. **Routing:** Ability to define GET routes with optional parameters and specific methods.
2. **Controllers:** Separate controllers for handling different endpoints (AboutController and MainController).
3. **Middleware:** Includes an *AuthMiddleware* that checks for the presence of an Authorization header and returns a 401 Unauthorized response if it's absent.
The *Rate limiting middleware* restricts the number of requests a user can make to an API within a set period, protecting the service from abuse and ensuring equitable access.
The sliding window approach continuously updates request counts based on real-time data, providing a smooth and accurate enforcement of rate limits. Please keep in mind
that this is only for demonstration purposes and a token bucket algorithm, e.g. implemented with Redis, is a better way for a production environment to efficiently manage request bursts. To test the middleware, just refresh the site ten times to show the error message.
4. **JSON Responses:** All controllers send responses in JSON format, making it suitable for APIs.
5. **Dynamic URL Parameters:** Supports dynamic routing where parameters are embedded in the URL.
6. **Error Handling:** Robust capabilities for maintaining consistent system behavior.

## Project Structure
**AboutController:** Handles routes related to 'about' information. It can return hardcoded data or data based on URL parameters. <br />

**MainController:** Manages the main entry points of the application, returning a static message. <br />

**Middleware:** For basic authorization and rate limiting. <br />

**Router:** Core of the routing system, it matches requested URLs against registered routes and applies middlewares if specified. <br />

## Output
The following output is generated for the respective routes:
**Route: /**
```
{"message": "Lorem ipsum dolor sit amet"}
```
**Route: /auth**
*If Authorization header is set (to anything), output is the same as above.*
```
Unauthorized
```
**Route: /about**
```
{"name":"Fabian"}
```
**Route with variable inpupt (Steven): /about/Steven**
```
{"name":"Steven"}
```
**Route to test error handling: /500_test**
```
{"error":{"code":500,"message":"Class \"App\\Controller\\ErrorController\" not found"}}
```

## How to Run the Project
Feel free to use my docker repository and pull the project files to the web folder.
After that, make sure to have composer installed and run *composer dump-autoload* to
generate the autoload files.

## Next Steps to Improve and Extend the Project
1. **Middleware Enhancements:** Extend middleware capabilities to handle more cases such as rate limiting, CORS, etc.
2. **Enhanced Routing:** Support for more HTTP methods (POST, PUT, DELETE) and regex-based route patterns.
3. **Dependency Injection:** Implement a dependency injection container to manage class dependencies more effectively.
4. **Testing:** Setup unit and integration tests using frameworks like PHPUnit to ensure reliability and robustness.
5. **Database Integration:** Integrate a database to interact with dynamic data rather than static responses.
6. **Configuration Management:** Implement a configuration management system to handle different environments (development, testing, production).
7. **Logging:** Add logging capabilities to capture requests and errors for debugging and monitoring purposes.

This project provides a robust starting point for developing PHP-based applications requiring a custom routing system. It can be extended and customized to fit more specific needs or to scale up for larger applications.

## Project Disclaimer
This project is primarily a showcase and educational tool, designed to demonstrate the core concepts of routing, middleware, and the MVC architecture in PHP. It is not (yet) ready for real-world application use as it lacks comprehensive security, scalability, and robustness that such applications demand. It serves as a learning platform for developers to understand and implement basic patterns and practices in web development and to showcase my understanding of said concepts.

## Code Documentation
Throughout the source code, extensive comments have been added to aid other developers in learning and understanding the MVC architecture and how it can be applied in PHP. Each class and method is accompanied by clear, concise comments explaining their functionality and role within the application. This detailed documentation is aimed at helping beginners navigate and grasp the fundamental principles and operations of the system.
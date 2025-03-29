<div style="font-family: 'JetBrains Mono Light', 'Fira Code', monospace">
<div style='text-align: center;'>

# <span style='color:teal;'>_** Conventions**_</span>
#### Conventions, Guidelines, Rules and Standards in contribution to <span style='color:teal;'>_****_</span> Project

Version 0.1.1

</div># <span style='color:teal;'>_**Conventions**_</span>

---

<div style='text-align: center;'>

#### Pomechain Back-end Team
#### Oct 2023

</div>

---

### Overview and Purpose

This is a set of conventions for writing clean, reusable, testable and maintainable code in php language and Laravel framework in the  back-end api project.

#### Important note

As stated in the [PSR documentation](https://www.php-fig.org/psr),
> ‘The keywords **MUST**, **MUST NOT**, **REQUIRED**, **SHALL**, **SHALL NOT**, **SHOULD**, **SHOULD NOT**, **RECOMMENDED**, **MAY**, and **OPTIONAL** in this document are to be interpreted as described in [RFC 2119](https://datatracker.ietf.org/doc/html/rfc2119).’
 
Some of the standard recommendations are taken from the [PHP Standards Recommendations (PSRs)](https://www.php-fig.org/psr) to ensure a consistent and readable codebase. These cover (but are not limited to) coding style, file naming conventions, and basic code organization. You can check them after reading this document.

> But If any rule cited here coincides or conflicts with any PSR (or other) rule the effective is **this document**.

---

## 1. Requirements

To run and contribute to the project you need these installed in your machine:

    * PHP v8.4
    * composer v2.8
    * MySQL ^8
    * Laravel v12

---

## 2. Coding Style

### 2.1 General

 &nbsp; 2.1.1 Files MUST use only <?php tag.

 &nbsp; 2.2.2. Files MUST use only UTF-8 without BOM for PHP code.

 &nbsp; 2.2.3. Files SHOULD either declare symbols (classes, functions, constants, etc.) or cause side effects (e.g., generate output, change .ini settings, etc.) but SHOULD NOT do both.

 &nbsp; 2.2.4. All properties MUST have clear types.

 &nbsp; 2.2.5. All methods MUST have return types. (You should avoid ‘mixed’ return type as much as possible.)

 &nbsp; 2.2.5. It is RECOMMENDED to have an annotated comment for any method or function to describe its intents and usage instructions. Don't repeat the annotations for an implemented method of an already declared method in some interface or abstract class. use @inheritDoc instead.

### 2.2. Naming Conventions

 &nbsp; 2.2.1. Class names MUST be declared in PascalCase. (by Class we mean Classes, Abstract and Final Classes, Interfaces, Traits and Enums)

 &nbsp; 2.2.2. Class constants MUST be declared in all uppercase with underscore separators.

 &nbsp; 2.2.3.  Method names MUST be declared in camelCase.

 &nbsp; 2.2.4.  Use proper indentation: Each level of code indentation should be represented by four spaces. Avoid using tabs for indentation.

 &nbsp; 2.2.5.  The first curly brace after class name should come in  a new line like this:

```php
class User
{
 //
}
```

 and not this:

```php
class User {
 //
}
```

&nbsp; 2.2.5.  The first curly brace after 'if' (or loops, switch cases and match expressions) SHOULD come in the same line as this:

```php
    if ($someCondition) {
        doSomething();
    }
```

and NOT this:

```php
    if ($someCondition) 
    {
        doSomething();
    }
```

---

## 4. Directory Structures

### 4.1. General Rules

4.1.1 Namespaces and classes MUST follow PSR-4.

### 4.2. Structure

The overall directory structure of 'app' directory is this:

```bash
app/
├── Console/
├── Data/
    ├── CollectionDTO/
       ├── BaseCollectionDTO.php # mother of all collection DTOs
       └── UserCollcetionDTO.php
    ├── DTO/ # the only visible parts of data layer in services
    ├── Models/ # all project models
    ├── Repositories/ # all concrete repositories are here
    └── Resources/ # api resources
├── Domains/
    ├── Users/
        └─ Jobs/
             ├── FindUserByIdJob.php # atoms of logic (maximum SRP possible)
             └── FindUserByEmail.php
    ├── Http/
        └─ Jobs/
             ├── SendMessageResponseJobForApiAgentJob.php # response jobs
             ├── SendMessageResponseJobForAndroidJob.php 
             ├── SendDataResponseJobForAndroidJob.php 
             └── SendDataResponseJobForApiAgentJob.php
    └── AnotherDomain/
        └─ Jobs/
            └── SomeOtherJob.php
├── Exceptions/
├── Foundation/ # home of all shared codes
    ├── Composables/ # a wide variety of reusable traits
        ├── DTO/ # these composables(traits) are only usable inside DTOs
        ├── Migrations/ # only usable inside migrations
        └── Responses/ # only usable inside response jobs
    ├── Contracts/ # interfaces categorized by classes
        ├── Jobs # job interfaces (for possible different implementations) 
        └── Repositories # repository interfaces
    ├── Enums/ # home of all shared enums (statuses, types, quantities, ...)
    ├── ValueObjects/ # all shared value objects are here
        ├── Responses/ # the fundamental structure of responses lie here
           ├── Formatters/ # all possible response formatters are here
               └── ApiResponseFormatterValues.php # generic api formatter
           └── ResponseValues.php # The mother value-object of responses
    └── Utilities/ # all used libraries or custom libraries are here
       └── SomeCustomLibrary.php
├── Http/
├── Providers/
└── Services/
config/
database/ # migrations should be all here
tests/
```

### 4.2.1 Service directory structure 

```shell
app/
  ├── ...
  └── Services/
      ├── Blog/
         ├── Composables/ # those service-related reusable traits 
         ├── Contracts/ # service-related interfaces
         ├── Database/
            ├── Factories/
            └── Seeders/
         ├── Enums/
         ├── Features/
            └── V1/ # api features are versioned
                └── Admin/ # divide all admin and non-admin features
                    ├── CreatePostFeature.php # single-action 
                    ├── UpdatePostFeature.php
                    ├── DeletePostFeature.php
                    ├── GetPostsFeature.php
                    ├── ShowPostFeature.php
                    ├── DecidePostCommentFeature.php
                    ├── GetPostCommentsFeature.php
                    ├── ShowPostCommentFeature.php
                    └── CreatePostCommentFeature.php
                # non-admin features
                ├── GetPostsFeature.php
                ├── ShowPostFeature.php
                ├── GetPostCommentsFeature.php
                ├── ShowPostCommentFeature.php
                └── CreatePostCommentFeature.php
         ├── Operations/
         ├── Http/
             ├── Controllers/
                └── V1/ # controllers are versioned
                   └── Admin/ # divide all admin and non-admin controllers
                      ├── PostController.php # singular name
                      ├── DecidePostCommentController.php
                      └── PostCommentController.php
                   # non-admin controllers
                   ├── PostController.php
                   └── PostCommentController.php
             ├── Middlewares/
             ├── Requests/
         ├── Providers/
             ├── ServiceProvider.php # the only visible provider from outside
             ├── FeatureProvider.php # if different possible implementation of a feature exists
             ├── OperationProvider.php # if different possible implementation of an operation exists
             └── RouteServiceProvider.php # register all routes here
          └── routes/
              └── v1
                 ├── admin.php # divide admin-only apis
                 └── api.php
      ├── Order/ #other services
      ├── Payment/
      ├── Product/
      ├── Search/
      ├── Shipment/
      .
      .
      .
      └── SomeOtherService/
```

---

## 5. Architectural conventions && OOD

### 5.1. Overall Architecture

The overall architecture is widely inspired (but not only) by the [Lucid architectural concepts](https://lucidarch.dev/).  Please refer to the original documentation for a more detailed explanation. 

>  In case of any conflict between this document and the so-called Lucid docs, what is in effect is **This document**.

The action logics are categorized in three levels: 

1. **Jobs** (Mandatory and low-level)
2. **Operations** (optional and mid-level)
3. **Features** (Mandatory and high-level)

We will discuss these three terms and other architectural terms.

* **Jobs**: low-level, tiny, code-specific, reusable atomic logic. All job class names end with Job word like FindUserByEmailJob.php. Jobs implement only one public method: **handle**:

```php
namespace App\Domains\User\Jobs;

use App\Data\DTO\UserManagement\UserDTO;

class GetUserByEmailJob
{
    public function handle(string $email): ?UserDTO
    {
        //
    }
}
```

* **Operations**: mid-level bundles that use jobs in a determined order. They are mostly used when a series of jobs are being used in a specific order (and this order has an independent nature and can be encapsulated.) 
 
  Operations implement only one public method: run

```php
namespace App\Services\SomeService\Operations;

class NotifyUserOperation
{
    public function run(NotificationValues $notificationValues): bool
    {
        //
    }
}
```
 
* **Features**: High-level logic that bundles both operations and jobs. The only thing that the controller layer can see are feature classes. Features are in charge of implementing or configuration presenting responses. In other words, operations don’t know anything about presentation layer (e.g., http responses).
 
  Features implement only one public method: **serve**:

```php
namespace App\Services\SomeService\Features;

class StorePostFeature
{
    public function serve(StorePostRequest $storePostRequest): Response
    {
        //
    }
}
```

* **Services**: are in charge of packaging _the Area of Focus_. It should not contain Data-related classes or Domain logic. It MUST contain only features and operations and NOT jobs.
Controllers should not see anything except one single feature. (They should only call serve method on the dedicated feature):

```php
namespace App\Services\SomeService\Http\Controllers;

class StorePostController extends Controller
{
    public function __construct(private StorePostFeature $feature)
    {}

    public function store(StorePostRequest $request): Response
    {
        return $this->feature->serve($request);
    }
}
```

### 5.2. Distinction between Data and Behavior

 &nbsp; 5.2.1 Use **Value objects** and **DTO**s to transform data between different layers of software architecture. (DTOs are self-descriptive data structures and mostly equivalent to Eloquent models with this responsibility: holding and transferring database columns as their properties).

 look at `App\Data\DTO\UserDTO` class for a sample DTO. 

 &nbsp; 5.2.2 Methods MUST not accept more than two primitive arguments. (Use value objects or DTOs instead of multiple arguments)

 &nbsp; 5.2.3 Public methods SHOULD not accept an array as argument type. look at below code snippets:

Do this:

```php
namespace App\Data\Repositories;

use App\Foundation\Contracts\Repositories\PostRepositoryInterface;
use App\Data\DTO\PostDTO; 

class PostRepository implements PostRepositoryInterface
{
    /**
    * @inheritDoc
    */
    public function create(PostDTO $postDTO): PostDTO
    {
        // 
    }
}
```

and NOT this:

```php
namespace App\Data\Repositories;

use App\Foundation\Contracts\Repositories\PostRepositoryInterface;
use App\Data\Modles\Post; 

class PostRepository implements PostRepositoryInterface
{
    /**
    * @inheritDoc
    */
    public function create(string $title, string $excerpt, string $body): Post
    {
        // 
    }
}
```
and NOT this:

```php
namespace App\Data\Repositories;

use App\Foundation\Contracts\Repositories\PostRepositoryInterface;
use App\Data\Modles\Post; 

class PostRepository implements PostRepositoryInterface
{
    /**
    * @inheritDoc
    */
    public function create(array $userData): Post
    {
        // 
    }
}
```

### 5.3. OOD & principles

 &nbsp; conforming to these rules and principles leads to a cleaner, more readable and more maintainable code. among these DRY is a MUST. Most of these rules are well known among software engineers and specially back-end engineers like SOLID principles. Not always any one can implement a code that conforms to all of them 100%. but do your best. 

#### 5.3.1 Don't Repeat Yourself (DRY)

 &nbsp; Avoid code duplication by extracting reusable code into functions, classes, or shared utilities.

#### 5.3.2 Single Responsibility Principle (SRP)
 &nbsp; Each class or function should have a single responsibility or reason to change.

#### 5.3.3 Open-Closed Principle (OCP)

&nbsp; Classes should be open for extension but closed for modification.

#### 5.3.4 Liskov Substitution Principle (LSP)

 &nbsp; Subclasses should be substitutable for their base classes. (Ensure that subclasses adhere to the same interface and behavior as their parent classes. Violations of LSP may lead to unexpected errors and inconsistencies.)

#### 5.3.5 Interface Segregation Principle (ISP)

 &nbsp; Clients should not be forced to depend on interfaces they do not use.
Split large interfaces into smaller, more specific ones that define only the required methods.

 &nbsp;  This ensures that implementing classes are not burdened by unnecessary dependencies.

#### 5.3.6 Dependency Inversion Principle (DIP)

 &nbsp;  Depend on abstractions, not on concrete implementations.
 High-level modules should not depend on low-level modules; both should depend on abstractions.

#### 5.3.7 Use dependency injection

to introduce loose coupling and make code easier to test and maintain.
* You SHOULD NOT instantiate a dependency inside a method. 

 Do this:

```php
class StorePostJob
{
    // inject the needed dependency 
    public function __construct(
        private PostRepositoryInterface $repository
    )
    {}

    public function handle(PostDTO $postDto): PostDTO
    {
        return $this->repository->create($postDto)
    }
}
```

and NOT this:

```php
use App\Data\Repositories\PostRepository;

class StorePostJob
{
    public function handle(PostDTO $postDto): PostDTO
    {
        $repository = new PostRepository();
        
        return $repository->create($postDto)
    }
}
```

and NOT this:

```php
use App\Foundation\Contracts\Repositories\PostRepositoryInterface;

class StorePostJob
{
    public function handle(PostDTO $postDto): PostDTO
    {
        // Don't fool yourself by overusing IoC Container in this way 
        $repository = resolve(PostRepositoryInterface::class);
        
        return $repository->create($postDto)
    }
}
```

* DTOs and ValueObjects (or those objects that are being created using a factory pattern) when are being created **for the first time** in a request flow are an absolute exception.

#### 5.3.7 Encapsulate What Varies

 &nbsp; Identify the aspects of your code that are most likely to change in the future.
 Encapsulate those variable elements and separate them from the stable ones. (This allows for easier modification and maintenance of the codebase)

 &nbsp; Encapsulate common functionality and use it in multiple places to reduce redundancy.
 
 5.3.7.1 Put reusable traits in the `App/Foundation/Composables` namespace (directory) or if they are only related to a single service inside `App/Service/SomeService/Composables` and categorize them by the group of classes that use these traits (composables).

#### 5.3.8 Law of Demeter (LoD)

Each unit should have limited knowledge about others and talk only to its immediate friends.

Minimize dependencies between classes and reduce tight coupling.

#### 5.3.9 Favor Composition over Inheritance

Prefer object composition (and object aggregation) over inheritance to achieve code reuse and better adhering to ISP, rather than relying heavily on inheritance. 

Inheritance  should only be used when there is a strong hierarchical relationship between classes.

#### 5.3.10 Single-Level of Abstraction Principle (**SLAP**)

Modules, classes, functions (or methods) should focus on a single level of abstraction. (It makes the code more modular)
Avoid mixing low-level details with high-level concepts in the same code block.

#### 5.3.11 Law Of Least Astonishment (**LOLA**)

Write code in a way that is intuitive, and does not surprise other developers. Follow common conventions established by the chosen framework or language (and this document of course!).

----

---

### 6. Files, Directories, Classes and Responsibilities

#### 6.1. Foundation

#### 6.2. Data

#### 6.3. Eloquent Models

#### 6.4. DTOs & CollectionDTOs

#### 6.5. Resources

#### 6.6. Repositories

#### 6.7. Domains

#### 6.8. Jobs

#### 6.9. Services

#### 6.10. ValueObjects

#### 6.11. Composables

#### 6.12. Features

#### 6.13. Operations

#### 6.14. Service Container & Providers

* 6.14.1 only one provider per service will be registered in the `config/app.php` file and other providers of the service will be registered inside it.

#### 6.15. Controllers

#### 6.16. Enums

#### 6.16. Config files

#### 6.16. Route files

#### 6.17. Form Requests

#### 6.18. Middlewares

#### 6.18. Exceptions


## 7. Design Patterns

----

## 8. Database

----

## 9. Documentations

### 9.1. Code Documentations

----

## 10. APIs

### 10.1 API Design

In project,_ you should always design and implement RESTful APIs (and no other api design pattern unless specifically desired).

To have a better grasp on how to design RESTful APIs see [this](https://learn.microsoft.com/en-us/azure/architecture/best-practices/api-design). some of the more important and fundamental best practices are these:

* 10.1.1 Avoid using verbs, and PREFER using nouns. 

* 10.1.2 Use proper HTTP verbs to describe the API endpoint meaning

* 10.1.3 Use versioning for each set of APIs that you provide.

* 10.1.4 Versioned api routes and their corresponding controllers should reside in the dedicated directory like this:

```bash
Services/
├── Blog/
    .
    .
    .
    ├── Http/
        └── Controllers/
            └── V1/
                ├── GetPostsController.php
                ├── ShowPostController.php
                ├── DeletePostController.php
                ├── UpdatePostController.php
                └── StorePostController.php
        ├── Requests/
    ├── Providers/
       ├── ServiceProvider.php
       └── RouteServiceProvider.php
    ├── Routes/
       └── V1/
          ├── admin.php
          └── api.php
```

* 10.1.5 Divide admin, seller and user routes on dedicated route files: 
  * `admin.php` for admin-only routes 
  * `seller.php` for seller-only routes and 
  * `api.php` for user.
  * in the future if any new **class of roles** like _seller_ appeared in the application it will be treated and added like above.

* 10.1.6 use `GET` HTTP method to read data (single or as a list). You MUST not use it to write something directly.

* 10.1.7 use `POST` HTTP method to create a resource.

* 10.1.8 use `PUT` HTTP method to update a resource.

* 10.1.9 use `DELETE` HTTP method to delete a resource.

* 10.1.10 use `PATCH` HTTP method to update only a portion of a resource. (like deactivating an entity or banning a user)

* 10.1.11 use `OPTIONS` HTTP method to describe or explain something about the actual (non-options) endpoint. (like presenting request body schema and/or response schema). It can be a complement to API documentation if needed.

* 10.1.12 route file registration, adding common prefixes, applying common middlewares should all be done inside service `RouteServiceProvider` in `App\Services\SomeService\Providers\RouteServiceProvider` namespace.

* 10.1.13 as stated before, only one provider per service will be registered in the `config/app.php` file and other providers of the service will be registered inside it.

* This is a simple versioned CRUD API Design for a simple resource (blog post):

```php
GET /api/v1/posts/{exposableId} // read a single resource

GET /api/v1/posts // read a list of resources

POST /api/v1/posts // create a resource

PUT /api/v1/posts/{exposableId} // update a single resource

DELETE /api/v1/posts/{exposableId} // delete a single resource
```

Don't design like this:

```php
GET /api/v1/get-posts // BAD
```

or this:

```php
POST /api/v1/create-post // BAD
```

### 10.2 API Documentations

### 10.3 Responses

* 10.3.1 Divide Command and Query

&nbsp; Divide all resource-oriented APIs to two main responsibilities: **Query** and **Command**. any api that reads something is a **Query** api endpoint and any other api endpoint that changes the state of the server is a **Command** one. This concept of segregation comes from _CQRS pattern_.

* 10.1.6 Never provide any unnecessary resource data in a **Command** api endpoint response unless necessary. In other words client application should always use **Query** api endpoints to read something. This mistake especially happen in an update resource (PUT) or DELETE resource api. those api endpoints should only say that the command is executed correctly and nothing more.

## 11. Libraries, dependencies & packages

* 11.1. first-party native Laravel packages are always preferred over non-Laravel native packages.
* 11.2. You MUST NOT use deprecated and not-maintained or poorly documented libraries or packages.
* 11.3. You SHOULD NOT use libraries or packages that force some breaking changes in oo design or accepted and implemented convention. (Writing own-implemented libraries with an exhaustive amount of tests is highly preferred over _frameworky_ libraries that force their design to other non-related parts of the code.)

----

## 12. Security

### 12.1 SQL Injection attacks

### 12.2 No-SQL Injection attacks

### 12.3 Path traversal attacks

### 12.4 OS Command Injection attacks

### 12.5 Code Injection attacks

### 12.5 Log Injection attacks

----

## 13. Collaboration and Git

----

## 14. Testing

----
</div>
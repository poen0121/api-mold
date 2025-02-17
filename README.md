## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Getting Started

	Installation vendor package. 
	$composer install

	Copy environment file.
	$cp .env.example .env

	Create application key.
	$php artisan key:generate

	Create JWT secret key.
	$php artisan jwt:secret

	Create signature secret key.
	$php artisan signature:secret

	Create migrate table.
	$php artisan migrate:install

	Create migrate data.
	$php artisan migrate --seed

	Read client service data.
	$php artisan data:client-read
	Enter the default global client service app ID : 1002212294583
	
## API Documentation

	Create API online documentation.
	$php artisan apidoc:generate --force

	API online documentation route is "/doc" .

	API online documentation authorization mode can set IP whitelist or request authorization link.

	API online documentation authorization link from "api/v1/doc/auth" .

## Attach Kits

The third-party kit included below.

[l5-repository](http://andersonandra.de/l5-repository/)

[league/fractal](https://fractal.thephpleague.com/installation/)

[jwt-auth](https://jwt-auth.readthedocs.io/en/develop/)

[apidoc](https://github.com/mpociot/laravel-apidoc-generator)

[activitylog](https://github.com/spatie/laravel-activitylog)

[laravel-phone](https://github.com/Propaganistas/Laravel-Phone)

[guzzlehttp](https://github.com/guzzle/guzzle)

[simple-qrcode](https://github.com/SimpleSoftwareIO/simple-qrcode)

## Development Learning

### Main Build Artisan Commands :
```
$ [Update: APP Secret Key] $php artisan key:generate
$ [Update: JWT Secret Key] $php artisan jwt:secret
$ [Update: Signature Secret Key] $php artisan signature:secret
$ [Build: Migration Index Table] $php artisan migrate:install
$ [Build: Migration Data Table] $php artisan migrate --seed
$ [Build: Full Entity Component] $php artisan make:entity
$ [Build: Controller Entity] $php artisan make:rest-controller
$ [Build: Exception Code Component] $php artisan make:ex-code
$ [Build: Exception Code Language] $php artisan make:ex-converter
$ [Build: Request Component] $php artisan make:request
$ [Build: Response Component] $php artisan make:response
$ [Build: User Auth Model Component] $php artisan make:user-auth
$ [Build: Feature Component] $php artisan make:feature
$ [Build: SMS Notification Component] $php artisan make:notification-sms
$ [Build: Verifier Component] $php artisan make:verifier
$ [Build: Cell Component] $php artisan make:cell
$ [Build: API Documentation] $php artisan apidoc:generate --force
```

### Main Production Artisan Commands :
```
$ [Generate: System Parameters] $php artisan data:sp-add  
$ [Generate: Authority Column] $php artisan mg-column:append-authority  
$ [Generate: Feature Column] $php artisan mg-column:append-feature  
$ [Generate: Setting Column] $php artisan mg-column:append-setting  
$ [Generate: Unique Auth Column] $php artisan mg-column:append-unique-auth  
$ [Create: Ban Service Configuration] $php artisan config:add-ban-service  
```

### Data Operation Artisan Commands :
```
$ [Create: Client Service] $php artisan data:client-add  
$ [Read: Client Service] $php artisan data:client-read  
$ [Edit: Client Service] $php artisan data:client-edit  
$ [Read: System Parameters] $php artisan data:sp-read  
$ [Edit: System Parameters] $php artisan data:sp-edit  
$ [Clear: Expired Database Cache] $php artisan cache:db-expired-clear  
```

### Validation Rules - Validator :
```
@ [Mobile Phone - Global Region Example: +886933852661] phone:AUTO,mobile  
@ [Any Phone - Global Region Example: +8860229998110] phone:AUTO  
@ [Mobile Phone - Taiwan Region Example: +886933852661] phone:TW,mobile  
@ [Mobile Phone - Taiwan & China Regions Example: +886933852661] phone:TW,CN,mobile  
@ [Taiwan National ID Example: A113515664] taiwan_id_verifier  
@ [Amount Range with Floating Point Support Example: 100.00] amount_verifier:0.00,100.00 >> amount_verifier:min,max  
@ [Instance Object Type Verification Example: new Foo()] instanceof_verifier  
@ [Instance Object Name Verification Example: new Foo()] instanceof_verifier:Foo  
@ [Instance Multiple Object Names Verification Example: new Foo()] instanceof_verifier:Foo,...  
```

### Code Extension Function :
```
+ [Identity Authorization Operations] TokenAuth  
+ [System Parameter Operations] SystemParameter  
+ [Signature Handling] StorageSign  
+ [Time Period Signature Handling] StoragePeriod  
+ [Imprint Signature Handling] StorageImprint  
+ [Authorization Code Operations] StorageCode  
+ [Authorization Data Operations] StorageData  
+ [Language Text Extraction] Lang::dict  
+ [API Response Formatting] Response::success  
+ [Phone Number Parsing] Phone::parse  
```

### Development File Directory :
```
> Responses: Handles response data formatting, output directory.  
	app \ Http \ Controllers \ Responses (Related Command: $php artisan make:response)  
> Exception: Exception message code mapping file, output directory.  
	app \ Exceptions (Related Command: $php artisan make:ex-code)  
> Program library directory.  
	app \ Libraries  
> Exception: Exception message translation mapping file, output directory.  
	resources \ lang \ locale \ exception (Related Command: $php artisan make:ex-converter)  
```

### Configuration Files :
```
> API Service Authorization Configuration File.  
	config \ auth.php  
> API Service Permission Restriction Configuration File.  
	config \ ban.php  
> API Exception Error Code Translation Configuration File.  
	config \ exception.php  
> API Feature Component Index Configuration File.  
	config \ feature.php  
> API Parameter System Validator Configuration File.  
	config \ sp.php  
> API SMS Channel Basic Configuration File.  
	config \ sms.php  
> API Signature Storage Configuration File.  
	config \ signature.php  
> API Map Basic Storage Configuration File.  
	config \ map.php  
> API Notice Basic Configuration File.  
	config \ notice.php  
> API Janitor Basic Class Configuration File.  
	config \ janitor.php  
```

### Development Notes :
```
* Basic make: commands follow PSR-1 naming conventions to avoid unexpected errors in file paths.
* For the special command make:ex-converter, the full Exception namespace class name must be used to create the file to properly capture the Exception Class.
* To suppress Exception logging, modify the $dontReport array in app \ Exceptions \ Handler.php; parent class groups can also be suppressed.
* When using the l5-repository object Repository for Create or Update operations, if a Validator Class is specified, it will validate the data written to the database.
```

### l5-repository Development Guidelines : 
```
* Entities: Used only as Eloquent Models.
* Repositories: Assist Entities by handling business logic, injected into Controllers.
* Requests: Handle data validation for incoming requests, injected into Controllers.
* Controllers: Receive Requests and return Responses.
* Presenters: Handle data display logic, injected into Repositories.
* Transformers: Assist Presenters by providing the database data return format.
* Validators: Assist Repositories in handling data validation.
```

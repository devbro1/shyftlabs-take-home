This is the base project with frontend reactjs and backend laravel10.\*

## conventions to follow

https://airbnb.io/javascript/react/#basic-rules
https://blog.stoplight.io/crud-api-design
https://github.com/themesberg/flowbite-admin-dashboard

### extra conventions:

```
PHP
Classname: ClassNameofMyChoosing
function: checkFunctionName
function parameter: $functionParameterName
variables: $var_name_meow
constants: CONSTANT_NAME

urls, uri: kebab-cased-names, opt for plural as often as possible
```

api reponse format:
on error:

```
HTTP_CODE >= 400
$['status'] = ''; //OK, validation_error, internal_error, or general_error
$['message'] = ''; //human readable message about the outcome, error message to show on top of the form
$['errors'] = []; //optional, error messages for specific fields
$['data'] = ???; //whatever data that may be relevenant to the api
$['XYZ'] = ???; //user data related to XYZ api if needed
```

on success:
HTTP\_CODE = 200

```
$[???] //whatever data the api is supposed to return but must be an array
```

## Dealing with JS Arrays/Objects:

To make life easier, use lodash:

```
_.forEach(whatever, function(value,key)=>{
  console.log([key,value]);
});
```

## Migration vs Seed vs Factory

Seeder should be used to add data for purpose of testing/demo/staging
Factory should be used to create RANDOM data for testing if needed

any data that needs to get to prod, should be done as part of migration by calling correct seeder OR directly from inside migration code.

## Automated Testing:

for frontend playwright is to be used
use https://fakerjs.dev/api/ for examples of faker values

to prepare for testing:
chmod -R 777 storage
php artisan migrate:fresh;
php artisan devbro:SADUpdatePerms;
php artisan db:seed TestingDataSeeder;

php artisan migrate:fresh;php artisan devbro:SADUpdatePerms;php artisan db:seed TestingDataSeeder;php artisan test;

backend testing can be done using:
php artisan test --filter CLASSNAME
php artisan test --coverage

Front-end testing and end-to-end testing can be done using:
npx playwright install # to install browsers if needed
yarn ptest

other useful commands:
yarn ptest --ui
yarn ptest --debug
yarn ptest --headed
yarn ptest FILE_NAME
npx playwright show-report
npx playwright codegen

it is useful to see console output during headless testing:

```
page.on("console", (message) => {
    console.log(message.text())
  })
```

```
page.on("response",(response) => {
  console.log(await response.text());
})
```

## some aspects to consider

the backend should only interact as API endpoint, it will NEVER generate an html page as a whole or partially!

all database queries should be done through their respective model. direct query should avoided but is ok if performance is a major factor.

new migration code should be in a new file. avoid modifying existing migrations.

## ACCESS CONTROL

The access control is based on RBAC and ABAC model and imlpemented through SPATIE/laravel-permission:

1. users can have many roles
2. roles can have many permissions
3. users can have many direct permissions
4. Users's full permissions is all the direct permissions plus all permissions inherited through their roles
5. ACL grants if user has given permissions (Not Roles)
6. each permission grants access, permissions should give access not take away (Ex. "hide email report" is not acceptable. the context being user is take away access to see email report)

we do not use Roles of a user for ACL verification.

there exists a method $user->forceAllow(['perms','roles'],[$context]) where it throws exception if any given perms or roles is missing
or if there exists a gate with a given name as perms or roles. $context array is used only for being passed to a given gate

All permissions and roles have:

name: internal name, should be lowercase unless it abbreviation, spaces are ok.

Description: human name or short text giving some explanation for what it is for, Should capitalize words, space is preferred: "Add Announcement", "Prescribe NSAID", "Write Review"

UNREGISTRED/Guest is a user that has not logged in yet, their only grant should be login and viewing static public pages

SAD user is member of all groups
a user may have one more roles in the system
roles do not have heirchy

When creating new Permissions, it should not have deny intention, to prevent deny, it should simply be unassigned

**AclEnforcerMiddleware** has been loaded into middleware. It will automatically load any policy that it can find for a given Controller.
it will find look at name of controller App\\Http\\Controllers\\XXXController and file respective policy class App\\Http\\Policies\\XXXPolicy.
for each method that is being called it will use the conversion methods described in laravel docs for Controller to model.
If the method is something that is not defined then a matching name method would be loaded from policy class to verify authorization.
example: UserController->getDefaultUser() would require UserPolicy->getDefaultUser() to return true. If Policy method is not defined, it would be considered as true.
if policy return anything other than TRUE, it will throw a 403 HTTP error.

## basic backend setup

```
yarn global add secure-spreadsheet
chmod -R 777 storage
composer install
php artisan config:cache;
php artisan migrate:fresh;
php artisan db:seed TestingDataSeeder;
php artisan devbro:SADUpdatePerms;
```

we may need to run

update oauth\_clients set secret = null;

this will allow oauth/token to allow public login without needed client\_secret on login page. If you want to use client\_secret, copy "Password grant client created successfully." to front end signin page

## Setting up Queued Tasks, delayed\_jobs,

We assume that long running jobs will be processed through queue. to bring up a queue worker do following commands:

```
cd backend
php artisan queue:work --timeout=3000 --memory=1024
```

## api documentation
Examples for all api examples are extracted from tests. so write a test and its response/requests will be copied into api docs.

API documentation uses two different pacakges. scribe is the primary package that generate openapi.yaml file.
l5-swagger is the second package which has a much nicer swagger interface. We can remove l5-swagger at anytime but scribe is essential.


We can access docs from two different urls:
```
http://localhost/api/docs <-- scribe
http://localhost/api/documentation <-- swagger
```



to generate swagger doc run:
```
php artisan devbro:generateOpenAPIDocs
```

api docs will be written directly in the code.
api docs may not be up to date.

api docs can be accessed from: http://localhost/api/documentation

follow examples from https://github.com/DarkaOnLine/L5-Swagger/wiki/Examples for creating docs for each api

## audit logs

audit logging needs to be enabled on the model itself. please note that pivot data is not saved to audit at this point. a seperate approach for this may be needed.

# useful commands

## backend

```
php artisan make:controller TemplateController --resource
php artisan make:model ModelName -a
php artisan make:model --migration --factory --seed --controller --api --policy --test LeadDate
php artisan db:seed <-- should not be used as all seeds must happen through migration, seed should be for testing only
php artisan iseed workflows,workflow\_nodes,workflow\_edges,action\_workflow\_node
composer outdated
composer require "laravel/framework:8.35.1"
vendor/bin/parallel-lint --exclude vendor .
./vendor/bin/php-cs-fixer fix ./app/
php artisan devbro:oa-prettier
```

## Frontend

yarn lint

## build

cd frontend
cp .env.example .env
yarn build

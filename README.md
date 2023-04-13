# private-practice
This is the base project with frontend reactjs and backend laravel8.*


## conventions to follow
https://airbnb.io/javascript/react/#basic-rules
https://blog.stoplight.io/crud-api-design

### extra conventions:
```

PHP:
Classname: ClassNameofMyChoosing
function: functionName
function parameter: $functionParameterName
variables: $var_name_meow
constants: CONSTANT_NAME
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
HTTP_CODE = 200
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
use https://rawgit.com/Marak/faker.js/master/examples/browser/index.html for examples of faker values

run headless testing with ???

to prepare for testing:
chmod -R 777 storage
php artisan migrate:fresh;
php artisan devbro:SADUpdatePerms;
php artisan db:seed TestingDataSeeder;

php artisan migrate:fresh;php artisan devbro:SADUpdatePerms;php artisan db:seed TestingDataSeeder;php artisan test --filter AppointmentsTest;

backend testing can be done using:
php artisan test --filter FILENAME
php artisan test --coverage

Front-end testing and end-to-end testing can be done using:
npx playwright install <-- to install playwright
npx playwright test

it might be useful to see console output during headless testing:
```
page.on("console", (message) => {
    console.log(message.text())
  })
```

## File naming conventions:
???


## some aspects to consider
the backend can only interact as API endpoint, it will never generate an html page, ever.

all database queries should be done through their respective model. direct query should not be used as much as possible or when performance is super critical.

If new migration is to be made, entire db must be cleared and migration built from beginning of time. this behavior will be changed once the system is more stable

## ACCESS CONTROL
The access control is based on RBAC and ABAC model and imlpemented through SPATIE/laravel-permission:
1. users can have many roles
2. roles can have many permissions 
3. users can have many direct permissions
4. Users's full permissions is all the direct permissions plus all permissions inherited through their roles
5. ACL grants if user has given permissions (Not Roles)

we do not use Roles of a user for ACL verification.

there exists a method $user->forceAllow(['perms','roles'],[$context]) where it throws exception if any given perms or roles is missing
or if there exists a gate with a given name as perms or roles. $context array is used only for being passed to a given gate


All permissions and roles have:

name: internal name, should be lowercase unless it abbreviation, no space(use _ instead), it is better to start with a verb: add_announcement, prescribe_NSAID, write_review

Description: human name or short text giving some explanation for what it is for, Should capitalize words, space is preferred: "Add Announcement", "Prescribe NSAID", "Write Review"

UNREGISTRED/Guest is a user that has not logged in yet, their only grant should be login and viewing static public pages

SAD user is member of all groups
a user may have one more roles in the system
roles do not have heirchy

When creating new Permissions, it should not have deny intention, to prevent deny, it should simply be unassigned

AclEnforcerMiddleware has been loaded into middleware. It will automatically load any policy that it can find for a given Controller.
it will convert App\Http\Controllers\XXXController to App\Http\Policies\XXXPolicy.
for each method that is being called it will use the conversion methods described in laravel docs for Controller to model.
If the method is something that is not defined then a matching name method would be loaded from policy class to verify authorization.
example: UserController->getDefaultUser() would require UserPolicy->getDefaultUser() to return true. If Policy method is not defined, it would be considered as true.
if policy return anything other than TRUE, it will throw a 403 HTTP error.

## basic backend setup
```
chmod -R 777 storage
composer update
php artisan config:cache
php artisan migrate:fresh
php artisan db:seed TestingDataSeeder
php artisan devbro:SADUpdatePerms
```

we may need to run

update oauth_clients set secret = null;

this will allow oauth/token to allow public login without needed client_secret on login page. If you want to use client_secret, copy "Password grant client created successfully." to front end signin page

## api documentation
to generate swagger doc run:
```
php artisan l5-swagger:generate #basic api-doc generation
php artisan devbro:AddOpenAPIExamples #loads api-doc with examples
```

api docs will be written directly in the code.
api docs may not be up to date.

api docs can be accessed from: http://localhost/api/documentation

follow examples from https://github.com/DarkaOnLine/L5-Swagger/wiki/Examples for creating docs for each api

## audit logs
audit logging needs to be enabled on the model itself. please note that pivot data is not saved to audit at this point. a seperate approach for this may be needed.

# useful commands
## backend
php artisan make:controller TemplateController --resource
php artisan make:model ModelName -a
php artisan make:model --migration --factory --seed --controller --api --policy --test LeadDate
php artisan db:seed <-- should not be used as all seeds must happen through migration, seed should be for testing only
php artisan iseed workflows,workflow_nodes,workflow_edges,action_workflow_node
composer outdated
composer require "laravel/framework:8.35.1"
vendor/bin/parallel-lint --exclude vendor .
./vendor/bin/php-cs-fixer fix ./app/
php artisan devbro:oa-prettier
php artisan devbro:resetLeads #sets all leads to their start status

## Frontend
npx prettier --write ./src/

## Testing
npm i -g playwright
command is: npx playwright test --headed --browser=chromium --workers 1
code generator: npx playwright codegen

## build
cd ~/source_code/frontend
yarn build
scp -r ./build pokogame@pokogames.com:/home1/pokogame/private-practice/frontend/build-upload/
ssh pokogame@pokogames.com 'cd /home1/pokogame/private-practice/frontend/ | rm -rf build/static/ | mv -f build-upload/* build/'


# Naming Convention Types:
kebab-cased-names: urls, uri, opt for plural as often as possible
KamelCaseClassName: class names
snake_case: variables
SCREAMING_SNAKE_CASE: constants



# schema for paginated data
```

 /**
 *  @OA\Schema(
 *  schema="Results",
 *  title="Schema reference for multiple objects",
 *    @OA\Property(
 *      property="current_page",
 *      example="1",
 *      type="integer",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="data",
 *      example="1",
 *      type="array",
 *      description="List of all objects",
 *      @OA\Items()
 *      ),
 *      )
 *    @OA\Property(
 *      property="first_page_url",
 *      example="http://localhost/api/v1/announcements?page=1",
 *      type="string",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="from",
 *      example="1",
 *      type="integer",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="last_page",
 *      example="3",
 *      type="integer",
 *      description="last page number",
 *      ),
 *    @OA\Property(
 *      property="last_page_url",
 *      example="http://localhost/api/v1/announcements?page=3",
 *      type="string",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="links",
 *      example="1",
 *      type="array",
 *      description="links for pagination",
 *      @OA\Items()
 *      ),
 *    @OA\Property(
 *      property="next_page_url",
 *      example="http://localhost/api/v1/announcements?page=2",
 *      type="string",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="path",
 *      example="http://localhost/api/v1/announcements",
 *      type="string",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="per_page",
 *      example="1",
 *      type="integer",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="prev_page_url",
 *      example="null",
 *      type="string",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="to",
 *      example="1",
 *      type="integer",
 *      description="",
 *      ),
 *    @OA\Property(
 *      property="total",
 *      example="1",
 *      type="integer",
 *      description="",
 *      ),
 *      )
 */
```
# Lumen API query parser
[ ![Codeship Status for ngabor84/lumen-api-query-parser](https://app.codeship.com/projects/8fd47630-122f-0135-959f-3611331b8dcf/status?branch=master)](https://app.codeship.com/projects/216941)

## Description
This is a simple request query parameter parser for REST-APIs based on Laravel's Lumen framework.

## Requirements
- PHP >=7.1
- Lumen framework >= 5.4
- Mockery >= 0.9 (dev)
- PHPUnit >= 6.1 (dev)
- PHP CodeSniffer >= 3.0.0 RC4 (dev)

## Installation
- Add ngabor84/lumen-api-query-parser to your composer.json and make composer update, or composer require ngabor84/lumen-api-query-parser ~1.0
- Setup the service provider:
    in bootstrap/app.php add the following line:
    ```php
    $app->register(LumenApiQueryParser\Provider\RequestQueryParserProvider::class);
    ```
    
## Usage
```php
    // app/API/V1/Models/UserController.php
    namespace App\Api\V1\Http\Controllers;
    
    use App\Api\V1\Models\User;
    use App\Api\V1\Transformers\UserTransformer;
    use LumenApiQueryParser\ResourceQueryParserTrait;
    use LumenApiQueryParser\BuilderParamsApplierTrait;
    
    class UserController extends Controller
    {
        use ResourceQueryParserTrait;
        use BuilderParamsApplierTrait;
                
        public function index(Request $request)
        {
            $params = $this->parseQueryParams($request);
            $query = User::query();
            $userPaginator = $this->applyParams($query, $params);
            
            $this->response->paginator($userPaginator, new UserTransformer, ['key' => 'users']);
        }
    }
```

## Query syntax

### Eager loading
Q: /users?connection[]=profile  
R: will return the collection of the users with their profiles included

### Filtering
Q: /users?filter[]=name:ct:admin    
R: will return the collection of the users whose names contains the admin string

__Available filter options__    

| Operator      | Description           | Example |
| ------------- | --------------------- | ------- |
| ct            | String contains       | name:ct:Peter |
| sw	        | String starts with    | username:sw:admin |
| ew	        | String ends with      | email:ew:gmail.com |
| eq	        | Equals                | level:eq:3 |
| ne	        | Not equals            | level:ne:4 |
| gt	        | Greater than          | level:gt:2 |
| ge	        | Greater than or equal | level:ge:3 |
| lt	        | Lesser than           | level:lt:4 |
| le	        | Lesser than or equal  | level:le:3 |
| in	        | In array              | level:in:1&#124;2&#124;3 |

### Sorting
Q: /users?sort[]=name:ASC   
R: will return the collection of the users sort by their names ascending

### Pagination
Q: /users?limit=10&page=3   
R: will return a part of the collection of the users (from the 21st to 30th)
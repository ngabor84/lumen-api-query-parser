# Lumen API query parser

## Description
This is a simple request query parameter parser for REST-APIs based on Laravel's Lumen framework.

## Requirements
- PHP >=7.1
- Lumen framework >= 5.4
- Mockery >= 0.9
- PHPUnit >= 6.1
- PHP CodeSniffer >= 3.0.0 RC4

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
            $this->applyParams($query, $params);
            $users = $query->get();
            
            $this->response->paginator($users, new UserTransformer, ['key' => 'users']);
        }
    }
```
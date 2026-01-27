# PHP_Laravel12_API_Versioning

# Step 1: Install Laravel 12
```php
composer create-project laravel/laravel PHP_Laravel12_API_Versioning
```
# Step 2: Setup Project Folder
 <img width="980" height="265" alt="image" src="https://github.com/user-attachments/assets/5256eed7-c736-4a0e-b668-1eb00ea9720a" />

# Step 3: Create Two Controller
Run Command For Terminal
```php
php artisan make:controller Api/V1/ProductController
php artisan make:controller Api/V2/ProductController
```
# app/Http/Controllers/Api/V1/ProductController.php
```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'version' => 'v1',
            'products' => [
                ['id' => 1, 'name' => 'Laptop V1'],
            ]
        ]);
    }
}

```
# app/Http/Controllers/Api/V2/ProductController.php
```php
<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'version' => 'v2',
            'products' => [
                ['id' => 1, 'name' => 'Laptop V2'],
                ['id' => 2, 'name' => 'Mobile V2'],
            ]
        ]);
    }
}
```
# Step 4:  Create Api Route for routes/api.php file
```php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController as ProductV1;
use App\Http\Controllers\Api\V2\ProductController as ProductV2;

Route::prefix('v1')->group(function () {
    Route::get('products', [ProductV1::class, 'index']);
});

Route::prefix('v2')->group(function () {
    Route::get('products', [ProductV2::class, 'index']);
});
```
# Step 5: Defining api.php routes for bootstrap/app.php
```php
 api: __DIR__.'/../routes/api.php',  Added This
```

# Step 6: Run This Server
```php

php artisan serve
```
# Test This url For Postman

# Open Postman and select Get Method and paste this url then click send button
<img width="628" height="184" alt="image" src="https://github.com/user-attachments/assets/589ca34e-dc00-4dd8-b74e-345b73850fd1" />

 
 
<img width="628" height="184" alt="image" src="https://github.com/user-attachments/assets/e28bb45a-62eb-4ba7-b967-a1936eeaf39f" />




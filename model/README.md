# Model Classes - CRUD Operations

This directory contains all model classes for the laptop_shop application. Each model extends the base `Model` class and provides CRUD (Create, Read, Update, Delete) operations.

## Base Model Class

The `Model.php` class provides common database operations:
- `getAll()` - Get all records
- `getById($id)` - Get a single record by ID
- `create($data)` - Create a new record
- `update($id, $data)` - Update a record
- `delete($id)` - Delete a record
- `findBy($column, $value)` - Find records by column
- `findOneBy($column, $value)` - Find a single record by column

## Available Models

### 1. User Model (`User.php`)
- `getByEmail($email)` - Get user by email
- `verifyPassword($email, $password)` - Verify user credentials
- `create($data)` - Create user with password hashing
- `update($id, $data)` - Update user with optional password hashing

### 2. Role Model (`Role.php`)
- `getByName($name)` - Get role by name
- Standard CRUD operations

### 3. Category Model (`Category.php`)
- `getByName($name)` - Get category by name
- Standard CRUD operations

### 4. Factory Model (`Factory.php`)
- `getByName($name)` - Get factory by name
- Standard CRUD operations

### 5. Product Model (`Product.php`)
- `getByCategory($categoryId)` - Get products by category
- `getByFactory($factoryId)` - Get products by factory
- `getAllWithDetails()` - Get products with category and factory info
- `getByIdWithDetails($id)` - Get product with details
- `search($searchTerm)` - Search products by name
- `updateQuantity($id, $quantity)` - Update product quantity

### 6. Cart Model (`Cart.php`)
- `getByUserId($userId)` - Get cart by user ID
- `getOrCreate($userId)` - Get or create cart for user

### 7. CartDetail Model (`CartDetail.php`)
- `getByCartId($cartId)` - Get cart items by cart ID
- `getByCartIdWithProducts($cartId)` - Get cart items with product info
- `getByCartAndProduct($cartId, $productId)` - Get specific cart item
- `addOrUpdate($cartId, $productId, $quantity)` - Add or update item in cart
- `deleteByCartId($cartId)` - Clear all items from cart

### 8. Order Model (`Order.php`)
- `getByUserId($userId)` - Get orders by user ID
- `getAllWithUsers()` - Get all orders with user info
- `getByIdWithUser($id)` - Get order with user info
- `getByStatus($status)` - Get orders by status
- `updateStatus($id, $status)` - Update order status

### 9. OrderDetail Model (`OrderDetail.php`)
- `getByOrderId($orderId)` - Get order items by order ID
- `getByOrderIdWithProducts($orderId)` - Get order items with product info
- `getOrderTotal($orderId)` - Calculate order total
- `createMultiple($items)` - Create multiple order details at once

## Usage Examples

### User Model
```php
require_once __DIR__ . '/model/User.php';

$userModel = new User();

// Create user
$userId = $userModel->create([
    'email' => 'user@example.com',
    'password' => 'password123',
    'full_name' => 'John Doe',
    'role_id' => 1
]);

// Verify login
$user = $userModel->verifyPassword('user@example.com', 'password123');

// Get user by ID
$user = $userModel->getById(1);

// Update user
$userModel->update(1, ['full_name' => 'Jane Doe']);
```

### Product Model
```php
require_once __DIR__ . '/model/Product.php';

$productModel = new Product();

// Get all products with details
$products = $productModel->getAllWithDetails();

// Get products by category
$products = $productModel->getByCategory(1);

// Search products
$products = $productModel->search('laptop');

// Create product
$productId = $productModel->create([
    'name' => 'Laptop XYZ',
    'price' => 999.99,
    'quantity' => 10,
    'factory_id' => 1,
    'category_id' => 1
]);
```

### Cart Model
```php
require_once __DIR__ . '/model/Cart.php';
require_once __DIR__ . '/model/CartDetail.php';

$cartModel = new Cart();
$cartDetailModel = new CartDetail();

// Get or create cart for user
$cart = $cartModel->getOrCreate(1);

// Add product to cart
$cartDetailModel->addOrUpdate($cart['cart_id'], 1, 2);

// Get cart items with product info
$items = $cartDetailModel->getByCartIdWithProducts($cart['cart_id']);
```

### Order Model
```php
require_once __DIR__ . '/model/Order.php';
require_once __DIR__ . '/model/OrderDetail.php';

$orderModel = new Order();
$orderDetailModel = new OrderDetail();

// Create order
$orderId = $orderModel->create([
    'user_id' => 1,
    'status' => 'pending'
]);

// Add order items
$orderDetailModel->createMultiple([
    ['order_id' => $orderId, 'product_id' => 1, 'quantity' => 2, 'price' => 999.99],
    ['order_id' => $orderId, 'product_id' => 2, 'quantity' => 1, 'price' => 599.99]
]);

// Get order total
$total = $orderDetailModel->getOrderTotal($orderId);
```

## Notes

- All models use PDO prepared statements to prevent SQL injection
- Password hashing is automatically handled in the User model
- The base Model class provides common CRUD operations
- Each model can be extended with custom methods as needed
- Database connection is shared via global $conn variable from config/database.php

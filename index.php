<?php
class Product {
    private $id;
    private $name;
    private $description;
    private $price;
    private $category;
    private $images = [];

    public function __construct($id, $name, $description, $price, $image, $category) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        array_push($this->images, $image);
        $this->category = $category;
    }

    public function getId() {
        return $this->id;
    }

    public function getPrice() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImages() {
        return $this->images;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getProductInfo() {
        return [
            'Идентификатор' => $this->id,
            'Название' => $this->name,
            'Описание' => $this->description,
            'Цена' => $this->price,
            'Категория' => $this->category,
            'Фото' => $this->image,
            'Вес' => $this->weight
        ];
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPrice($price) {
        if ($price > 0) {
            $this->price = $price;
        } else {
            throw new Exception("Цена должна быть положительной.");
        }
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function addImage($image) {
        array_push($this->images, $image);
    }

    public function removeImage($image) {
        $key = array_search($image, $this->images);
        if ($key !== false) {
            unset($this->images[$key]);
        }
    }
}

class DigitalProduct extends Product {
    private $fileSize;

    public function __construct($id, $name, $description, $price, $image, $category, $fileSize) {
        parent::__construct($id, $name, $description, $price, $image, $category);
        $this->fileSize = $fileSize;
    }

    public function getFileSize() {
        return $this->fileSize;
    }

    public function getProductInfo() {
        return [
            'Идентификатор' => $this->id,
            'Название' => $this->name,
            'Описание' => $this->description,
            'Цена' => $this->price,
            'Размер файла' => $this->fileSize,
            'Категория' => $this->category,
            'Фото' => $this->image,
            'Вес' => $this->weight
        ];
    }
}

class PhysicalProduct extends Product {
    private $quantity;

    public function __construct($id, $name, $description, $price, $image, $category, $quantity) {
        parent::__construct($id, $name, $description, $price, $image, $category);
        $this->quantity = $quantity;
    }

    public function isAvailable() {
        return $this->quantity > 0;
    }

    public function reduceQuantity($amount) {
        if ($this->quantity >= $amount) {
            $this->quantity -= $amount;
        } else {
            throw new Exception("Недостаточно товара на складе");
        }
    }

    public function addQuantity($quantity) {
        $this->quantity += $quantity;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getProductInfo() {
        return [
            'Идентификатор' => $this->id,
            'Название' => $this->name,
            'Описание' => $this->description,
            'Цена' => $this->price,
            'Количество на складе' => $this->quantity,
            'Категория' => $this->category,
            'Фото' => $this->image,
            'Вес' => $this->weight
        ];
    }
}

class Cart {
    private $items = [];

    public function addProduct(Product $product, $quantity = 1) {
        $productId = $product->getProductInfo()['Идентификатор'];

        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] += $quantity;
        } else {
            $this->items[$productId] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
    }

    public function removeProduct($productId) {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
        }
    }

    public function updateQuantity($productId, $quantity) {
        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] = $quantity;
        }
    }

    public function getTotalPrice() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    public function clear() {
        $this->items = [];
    }

    public function getItems() {
        return $this->items;
    }

    public function isEmpty() {
        return empty($this->items);
    }
}

class User {
    private $id;
    private $name;
    private $email;
    private $passwordHash;
    private $phone;
    private $address;
    private $isAdmin;

    public function __construct($id, $name, $email, $password, $phone = '', $address = '', $isAdmin = false) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->phone = $phone;
        $this->address = $address;
        $this->isAdmin = $isAdmin;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function isAdmin() {
        return $this->isAdmin;
    }

    public function updateProfile($name, $phone, $address) {
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
    }

    public function changePassword($oldPassword, $newPassword) {
        if (password_verify($oldPassword, $this->passwordHash)) {
            $this->passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            return true;
        }
        return false;
    }

    public function authenticate($password) {
        return password_verify($password, $this->passwordHash);
    }
}

class Admin extends User {
    public function __construct($id, $name, $email, $password, $phone = '', $address = '') {
        parent::__construct($id, $name, $email, $password, $phone, $address, true);
    }

    public function addProduct(Product $product) {
        // добавление продукта в БД, например
    }

    public function deleteProduct(Product $product) {
        // удаление товара из БД
    }

    public function editProduct(Product $product, $newName, $newDescription, $newPrice) {
        $product->setName($newName);
        $product->setDescription($newDescription);
        $product->setPrice($newPrice);
    }

    public function manageUser(User $user, $action) {
        switch ($action) {
            case 'ban':
                echo "Пользователь " . $user->getName() . " заблокирован";
                break;
            case 'unban':
                echo "Пользователь " . $user->getName()} . " разблокирован";
                break;
            case 'delete':
                echo "Пользователь " . $user->getName()} . " удален";
                break;
            default:
                echo "Неправильное действие";
                break;
        }
    }
}

class Customer extends User {
    private $cart;
    private $orderHistory = [];

    public function __construct($id, $name, $email, $password, $phone = '', $address = '') {
        parent::__construct($id, $name, $email, $password, $phone, $address, false);
        $this->cart = new Cart();
    }

    public function addToCart(Product $product, $quantity = 1) {
        $this->cart->addProduct($product, $quantity);
    }

    public function removeFromCart(Product $product) {
        $this->cart->removeProduct($product->getId());
    }

    public function placeOrder() {
        if ($this->cart->isEmpty()) {
            echo "Корзина пуста. Заказ не может быть оформлен.\n";
            return;
        }

        $order = new Order($this->id, $this->cart, $this->address);
        $this->orderHistory[] = $order;

        $this->cart->clear();
        echo "Заказ оформлен. Спасибо за покупку!";
    }

    public function viewOrderHistory() {
        if (empty($this->orderHistory)) {
            echo "У вас нет заказов.";
        } else {
            foreach ($this->orderHistory as $order) {
                echo "Заказ №" . $order->getOrderId() . " - Дата: " . $order->getOrderDate();
            }
        }
    }

    public function leaveReview(Review $review) {
        // добавить отзыв в БД, например
    }
}

class Order {
    private $orderId;
    private $customerId;
    private $products = [];
    private $totalAmount = 0;
    private $orderDate;
    private $deliveryAddress;
    private $status;

    public function __construct($customerId, Cart $cart, $deliveryAddress) {
        $this->orderId = uniqid();
        $this->customerId = $customerId;
        $this->products = $cart->getItems();
        $this->totalAmount = $cart->getTotalPrice();
        $this->orderDate = date("Y-m-d H:i:s");
        $this->deliveryAddress = $deliveryAddress;
        $this->status = "Новый";
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getCustomerId() {
        return $this->customerId;
    }

    public function getProducts() {
        return $this->products;
    }

    public function getTotalAmount() {
        return $this->totalAmount;
    }

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function getDeliveryAddress() {
        return $this->deliveryAddress;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}

class Review {
    private $reviewId;
    private $customerId;
    private $productId;
    private $rating;
    private $comment;
    private $reviewDate;

    public function __construct($customerId, $productId, $rating, $comment) {
        $this->reviewId = uniqid();
        $this->customerId = $customerId;
        $this->productId = $productId;
        $this->setRating($rating);
        $this->comment = $comment;
        $this->reviewDate = date("Y-m-d H:i:s");
    }

    public function getReviewId() {
        return $this->reviewId;
    }

    public function getCustomerId() {
        return $this->customerId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        if ($rating >= 1 && $rating <= 5) {
            $this->rating = $rating;
        } else {
            throw new Exception("Рейтинг должен быть числом от 1 до 5.");
        }
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getReviewDate() {
        return $this->reviewDate;
    }
}

class Category {
    private $categoryId;
    private $name;
    private $description;
    private $parentId;

    public function __construct($categoryId, $name, $description, $parentId = null) {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->description = $description;
        $this->parentId = $parentId;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function setParentId($parentId) {
        $this->parentId = $parentId;
    }
}

class ContactForm {
    private $name;
    private $email;
    private $subject;
    private $message;

    public function __construct($name, $email, $subject, $message) {
        $this->name = $name;
        $this->setEmail($email);
        $this->subject = $subject;
        $this->message = $message;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception("Неверный формат электронной почты.");
        }
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getMessage() {
        return $this->message;
    }

    public function validate() {
        if (empty($this->name) || empty($this->email) || empty($this->subject) || empty($this->message)) {
            throw new Exception("Все поля обязательны для заполнения.");
        }
        return true;
    }

    public function send() {
        $this->validate();
        // отправка формы
    }
}

class ShippingAddress {
    private $recipientId;
    private $street;
    private $city;
    private $region;
    private $postalCode;
    private $country;

    public function __construct($recipientId, $street, $city, $region, $postalCode, $country) {
        $this->recipientId = $recipientId;
        $this->street = $street;
        $this->city = $city;
        $this->region = $region;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    public function getRecipientId() {
        return $this->recipientId;
    }

    public function setRecipientId($recipientId) {
        $this->recipientId = $recipientId;
    }

    public function getStreet() {
        return $this->street;
    }

    public function setStreet($street) {
        $this->street = $street;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getRegion() {
        return $this->region;
    }

    public function setRegion($region) {
        $this->region = $region;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->country = $country;
    }
}

class PaymentMethod {
    private $name;
    private $description;
    private $isAvailable;

    public function __construct($name, $description, $isAvailable = true) {
        $this->name = $name;
        $this->description = $description;
        $this->isAvailable = $isAvailable;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function isAvailable() {
        return $this->isAvailable;
    }

    public function setAvailability($isAvailable) {
        $this->isAvailable = $isAvailable;
    }
}

class Coupon {
    private $code;
    private $discountType;
    private $discountValue;
    private $expiryDate;
    private $isActive;

    public function __construct($code, $discountType, $discountValue, $expiryDate, $isActive = true) {
        $this->code = $code;
        $this->discountType = $discountType;
        $this->discountValue = $discountValue;
        $this->expiryDate = new DateTime($expiryDate);
        $this->isActive = $isActive;
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function getDiscountType() {
        return $this->discountType;
    }

    public function setDiscountType($discountType) {
        $this->discountType = $discountType;
    }

    public function getDiscountValue() {
        return $this->discountValue;
    }

    public function setDiscountValue($discountValue) {
        $this->discountValue = $discountValue;
    }

    public function getExpiryDate() {
        return $this->expiryDate;
    }

    public function setExpiryDate($expiryDate) {
        $this->expiryDate = new DateTime($expiryDate);
    }

    public function isActive() {
        return $this->isActive;
    }

    public function setActive($isActive) {
        $this->isActive = $isActive;
    }
}
?>
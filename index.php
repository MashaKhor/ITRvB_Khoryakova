<?php 

abstract class Product {
    protected $name;
    protected $price;

    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }

    abstract public function calculateFinalPrice();

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
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
}

class DigitalProduct extends Product {
    public function calculateFinalPrice() {
        return $this->price / 2;
    }
}

class PhysicalItemProduct extends Product {
    private $quantity;

    public function __construct($name, $price, $quantity) {
        parent::__construct($name, $price);
        $this->quantity = $quantity;
    }

    public function calculateFinalPrice() {
        return $this->price * $this->quantity;
    }

    public function getQuantity() {
        return $this->quantity;
    }
}

class WeightProduct extends Product {
    private $weight;

    public function __construct($name, $price, $weight) {
        parent::__construct($name, $price);
        $this->weight = $weight;
    }

    public function calculateFinalPrice() {
        return $this->price * $this->weight;
    }

    public function getWeight() {
        return $this->weight;
    }
}

?>
# Acme Widget Co Sales System - Basket Calculator

This PHP project provides a simple, yet powerful sales system for Acme Widget Co. The system allows adding products to a virtual basket and calculates the total cost, considering delivery charges and special offers.

## 🚀 Features

- Add products to the basket using their product code.
- Calculate the total cost of the basket considering:
  - Cost of the individual products.
  - Special offers.
  - Delivery charges based on the cost of the basket.

## 🧠 How It Works

The entire system revolves around the `Basket` class. This class is initialized with the product catalogue, delivery charge rules, and special offers.

- `add` method: Adds products to the basket using their product code.
- `total` method: Calculates and returns the total cost of the basket.

The `total` method goes through each unique product in the basket, applies any eligible special offers to calculate the product's cost, and adds it to the total cost. Once the cost of all products is computed, it determines the delivery charge based on the current total cost and adds this to the total cost.

Internally, the `Basket` class uses two helper methods:

- `applyOffers`: Applies any eligible special offers to a product.
- `getDeliveryCharge`: Determines the delivery charge based on the total cost of products.

## 📜 Assumptions

While developing this system, the following assumptions were made:

1. The inputs to the system (product catalogue, delivery charge rules, and special offers) are correctly formatted and valid.
2. The special offers are in the format of "buy N for the price of M". Each product can have at most one such offer.
3. The delivery charge rules and special offers are sorted in descending order by their minimum values.
4. A product can be added to the basket multiple times by calling the `add` method multiple times with the product code. The quantity of a product is not set directly.
5. All prices and costs are in the same currency, and the currency conversion is not a concern of this system.

## 🎯 Usage Example

```php
$catalogue = [
    'R01' => 32.95,
    'G01' => 24.95,
    'B01' => 7.95
];

$deliveryRules = [
    ['min' => 90, 'charge' => 0],
    ['min' => 50, 'charge' => 2.95],
    ['min' => 0, 'charge' => 4.95]
];

$offerRules = [
    'R01' => ['quantity' => 2, 'price' => 32.95 + 32.95/2]
];

$basket = new Basket($catalogue, $deliveryRules, $offerRules);
$basket->add('B01');
$basket->add('G01');
echo $basket->total();  // Outputs: 37.85
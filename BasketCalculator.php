<?php

declare(strict_types=1);

class Basket {
    private $catalogue = [];
    private $deliveryRules = [];
    private $offerRules = [];
    private $items = [];

    public function __construct(array $catalogue, array $deliveryRules, array $offerRules) {
        $this->catalogue = $catalogue;
        $this->deliveryRules = $deliveryRules;
        $this->offerRules = $offerRules;
    }

    public function add(string $productCode): void {
        $this->items[] = $productCode;
    }

    public function total(): float {
        $total = 0;
        $counts = array_count_values($this->items);

        foreach ($counts as $productCode => $count) {
            $price = $this->catalogue[$productCode];
            $total += $this->applyOffers($productCode, $price, $count);
        }

        $total += $this->getDeliveryCharge($total);

        return $total;
    }

    private function applyOffers(string $productCode, float $price, int $count): float {
        $total = 0;
        if (isset($this->offerRules[$productCode])) {
            $offer = $this->offerRules[$productCode];
            $total += ((int)($count / $offer['quantity']) * $offer['price']) +
                      ($count % $offer['quantity']) * $price;
        } else {
            $total += $count * $price;
        }

        return $total;
    }

    private function getDeliveryCharge(float $total): float {
        foreach ($this->deliveryRules as $rule) {
            if ($total >= $rule['min']) {
                return $rule['charge'];
            }
        }

        return end($this->deliveryRules)['charge'];
    }
}

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
$basket->add('B01');
$basket->add('R01');
$basket->add('R01');
$basket->add('R01');
echo $basket->total();
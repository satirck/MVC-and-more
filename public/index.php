<?php

declare(strict_types=1);

function filter_by_product($sales, $product): array{
    return array_filter($sales, static function($sale) use ($product) {
        return $sale['product'] === $product;
    });
}

function total_sales_by_product($sales): array {
    $productSales = [];
    foreach ($sales as $sale) {
        if (!isset($productSales[$sale['product']])) {
            $productSales[$sale['product']] = 0;
        }
        $productSales[$sale['product']] += $sale['amount'];
    }
    return $productSales;
}

function getCountSalesByProduct($sales): array{
    $products = array_column($sales, 'product');
    return array_count_values($products);
}

function getTotalSales($sales): int {
    return array_sum(array_column($sales, 'amount'));
}

$sales = [
    ['date' => '2024-05-01', 'product' => 'A', 'amount' => 100],
    ['date' => '2024-05-01', 'product' => 'B', 'amount' => 150],
    ['date' => '2024-05-02', 'product' => 'A', 'amount' => 200],
    ['date' => '2024-05-02', 'product' => 'C', 'amount' => 250],
    ['date' => '2024-05-03', 'product' => 'A', 'amount' => 300],
    ['date' => '2024-05-03', 'product' => 'B', 'amount' => 350],
];

$productASales = filter_by_product($sales, 'A');

$totalSalesByProduct = total_sales_by_product($sales);

$countSalesByProduct = getCountSalesByProduct($sales);

$totalSales = getTotalSales($sales);

echo 'Filtered by product A:<br>';
print_r($productASales);

echo 'Total V for each product:<br>';
print_r($totalSalesByProduct);

echo 'Products sales:<br>';
print_r($countSalesByProduct);



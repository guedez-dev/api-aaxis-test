<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class ValidatorService
{
    public function validateJson(string $json): array
    {
        $data = json_decode($json, true);

        if ($data === null) {
            throw new \InvalidArgumentException('JSON format error.');
        }

        return $data;
    }

    public function validateProductsArray(array $products, $method ): void
    {
        if (empty($products) || !is_array($products)) {
            throw new \InvalidArgumentException('Incorrect data format. An array of products is expected.');
        }

        if ($method === 'POST'){
            foreach ($products as $product) {
                if (!isset($product['product_name']) || !isset($product['description'])) {
                    throw new \InvalidArgumentException('Missing required fields for product.');
                }
            }
        }

    }

    public function validateProductFields(array $product): void
    {

        $skuMissing = !isset($product['sku']);
        $productNameMissing = !isset($product['product_name']);
        $descriptionMissing = !isset($product['description']);

        if ($skuMissing){
            throw new \InvalidArgumentException('Field SKU is required.');
        }

        if (($productNameMissing || $descriptionMissing) && count($product) < 2) {
            throw new \InvalidArgumentException('at least one field is required to update the product (product_name or description)');
        }


    }
}

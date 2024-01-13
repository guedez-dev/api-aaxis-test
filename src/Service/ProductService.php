<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductService
{
    private EntityManagerInterface $manager;
    private ?SkuGenerator $skuGenerator;
    private ?ValidatorService $validator;

    public function __construct(EntityManagerInterface $manager, ?SkuGenerator $skuGenerator , ?ValidatorService $validator)
    {
        $this->manager = $manager;
        $this->skuGenerator = $skuGenerator;
        $this->validator = $validator;
    }

    public function list(): JsonResponse
    {
        try {
            $productRep = $this->manager->getRepository(Product::class);
            $products = $productRep->findAll();

            $serializedProducts = array_map(function ($product) {
                return $product->jsonSerialize();
            }, $products);

            return new JsonResponse(['products' => $serializedProducts], 200);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error listing products.');
        }
    }

    public function create(string $requestData): JsonResponse
    {
        try {
            $data = $this->validator->validateJson($requestData);
            $this->validator->validateProductsArray($data['products'],'POST');

            $createdProduct = [];

            $this->manager->beginTransaction();

            foreach ($data['products'] as $productData) {
                $product = new Product();

                $product->setProductName($productData['product_name']);
                $product->setDescription($productData['description']);
                $product->setSku($this->skuGenerator->generator());

                $this->manager->persist($product);
                $this->manager->flush();

                $createdProduct[] = [
                    'id' => $product->getId(),
                    'sku'=> $product->getSku(),
                    'product_name' => $product->getProductName()
                ];
            }

            $this->manager->commit();

            return new JsonResponse(['message' => 'Operation completed successfully.', 'products' => $createdProduct], 200);
        } catch (\Exception $e) {
            $this->manager->rollback();
            return $this->handleException($e, 'Error creating products.');
        }
    }

    public function update(string $requestData): JsonResponse
    {
        try {
            $data = $this->validator->validateJson($requestData);
            $this->validator->validateProductsArray($data['products'],'PUT');

            $productRep = $this->manager->getRepository(Product::class);

            $info = [];
            foreach ($data['products'] as $productData) {
                $this->validator->validateProductFields($productData);

                $product = $productRep->findOneBy(['sku' => $productData['sku']]);
                if($product !== null){

                        if (array_key_exists('product_name', $productData)){
                            $product->setProductName($productData['product_name']);
                        }

                        if (array_key_exists('description',$productData)){
                            $product->setDescription($productData['description']);
                        }

                        $this->manager->persist($product);
                        $this->manager->flush();

                        $info[] = ['message' => 'Product with SKU: ' .
                                    $product->getSku() . ' updated'];

                }else{
                    $info[] = ['message' => 'Product with SKU: ' .
                                $productData['sku'] . ' not found'];
                }
            }

            return new JsonResponse(['result' => $info], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating products.');
        }
    }

    private function handleException(\Exception $e, string $message): JsonResponse
    {
        return new JsonResponse(['message' => $message, 'error' => $e->getMessage()], 500);
    }
}

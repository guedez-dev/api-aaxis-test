<?php

namespace App\Controller;

use App\Service\ProductService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SkuGenerator;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_product', methods: 'GET')]
    public function list(
        EntityManagerInterface $manager
    ): JsonResponse
    {
        $products = new ProductService($manager,null,null);
        return $products->list();
    }

    #[Route('/api/create-products', name: 'api_create_products', methods: 'POST')]
    public function create(
        Request $request,
        SkuGenerator $sku,
        EntityManagerInterface $manager,
        ValidatorService $validator
    ): JsonResponse
    {
        $productService = new ProductService($manager,$sku,$validator);
        return $productService->create($request->getContent());
    }

    #[Route('/api/update-products', name: 'api_update_products', methods: 'PUT')]
    public function update(
        Request $request,
        EntityManagerInterface $manager,
        ValidatorService $validator
    ): JsonResponse
    {
        $productService = new ProductService($manager,null,$validator);
        return $productService->update($request->getContent());
    }

}

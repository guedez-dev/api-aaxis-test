<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
Use App\Service\SkuGenerator;

class ProductFixtures extends Fixture
{

    private SkuGenerator $sku;

    public function __construct(SkuGenerator $sku)
    {
        $this->sku = $sku;
    }
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setProductName("Producto $i");
            $product->setDescription("Descripción del Producto $i");
            $product->setSku($this->sku->generator());
            $manager->persist($product);
        }

        $manager->flush();
    }
}
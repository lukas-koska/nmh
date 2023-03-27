<?php
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 1000 products
        for ($i = 0; $i < 1000; $i++) {
            $product = new Product();
            $product->setName('Product '.$i);
            $product->setDescription('Description for product '.$i);
            $product->setManufacturer('Manufacturer ' . round($i / 50) + 1);
            $product->setPrice(mt_rand(10, 100));
            $manager->persist($product);
        }
        $manager->flush();
    }
}

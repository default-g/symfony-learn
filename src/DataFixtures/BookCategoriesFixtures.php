<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new BookCategory())->setTitle('Fiction')->setSlug('fiction'));
        $manager->persist((new BookCategory())->setTitle('Kids')->setSlug('Kids'));
        $manager->persist((new BookCategory())->setTitle('Adventure')->setSlug('Adventure'));

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

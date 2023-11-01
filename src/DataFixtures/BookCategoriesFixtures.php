<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoriesFixtures extends Fixture
{
    public const FICTION = 'fiction';

    public const ADVENTURE = 'adventure';

    public function load(ObjectManager $manager): void
    {

        $categories = [
            self::FICTION =>(new BookCategory())->setTitle('Fiction')->setSlug('fiction')->setId(1),
            self::ADVENTURE => (new BookCategory())->setTitle('Adventure')->setSlug('Adventure')->setId(2)
        ];

        foreach ($categories as $category) {
            $manager->persist($category);
        }

        $manager->persist((new BookCategory())->setTitle('Kids')->setSlug('Kids')->setId(3));

        $manager->flush();

        foreach ($categories as $code => $category) {
            $this->addReference($code, $category);
        }
    }
}

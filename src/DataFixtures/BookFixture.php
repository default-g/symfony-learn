<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $fiction = $this->getReference(BookCategoriesFixtures::FICTION);
        $adventure = $this->getReference(BookCategoriesFixtures::ADVENTURE);

        $book = (new Book())
            ->setTitle('Cool book')
            ->setPublicationDate(new \DateTimeImmutable('2022-01-01'))
            ->setMeap(false)
            ->setAuthors(['Johnny'])
            ->setSlug('cool-book')
            ->setCategories(new ArrayCollection([$fiction]))
            ->setImage('/cool-image')
            ->setDescription('test description')
            ->setIsbn('123456789');

        $manager->persist($book);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookCategoriesFixtures::class,
        ];
    }
}

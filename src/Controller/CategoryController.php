<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/seed', name: 'category_seed')]
    public function seed(EntityManagerInterface $em): Response
    {
        $categories = ['Electronics', 'Suspension', 'Brakes', 'Engine Parts'];

        foreach ($categories as $catName) {
            $existingCategory = $em->getRepository(Category::class)->findOneBy(['name' => $catName]);
            if (!$existingCategory) {
                $category = new Category();
                $category->setName($catName);
                $em->persist($category);
            }
        }

        $em->flush();

        return new Response('Categories seeded!');
    }

    #[Route("/proj/brands/{categoryName}", name: "proj_brands_by_category")]
    public function projBrandsByCategory(string $categoryName, CategoryRepository $categoryRepository): Response
    {
        $posts = $categoryRepository->findPostsByCategoryName($categoryName);

        return $this->render('proj/brands-page.html.twig', [
            'posts' => $posts,
            'currentCategory' => $categoryName
        ]);
    }


    #[Route('/proj/categories', name: 'proj_category_list')]
    public function categoryList(): Response
    {
        return $this->render('proj/category.html.twig');
    }
}

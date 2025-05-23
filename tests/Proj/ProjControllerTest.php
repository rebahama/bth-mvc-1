<?php

namespace App\Tests;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Entity\Category;
use ReflectionClass;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
/**
 * Test cases for crud in the post and category entity class
 */
class ProjControllerTest extends TestCase
{
    public function testFindAllPosts()
    {
        $post = new Post();
        $post->setTitle('Test Post');
        $post->setDescription('Some description');

        $postRepository = $this->createMock(PostRepository::class);
        $postRepository->method('findAll')->willReturn([$post]);

        $result = $postRepository->findAll();
        $this->assertCount(1, $result);
        $this->assertEquals('Test Post', $result[0]->getTitle());
    }

    public function testFindAllCategories()
    {
        $category = new Category();
        $category->setName('Engine Parts');

        $categoryRepository = $this->createMock(CategoryRepository::class);
        $categoryRepository->method('findAll')->willReturn([$category]);

        $result = $categoryRepository->findAll();

        $this->assertCount(1, $result);
        $this->assertEquals('Engine Parts', $result[0]->getName());
    }

      public function testSetAndGetCategory()
    {
        $category = new Category();
        $category->setName('Brakes');

        $post = new Post();
        $post->setCategory($category);

        $this->assertInstanceOf(Category::class, $post->getCategory());
        $this->assertEquals('Brakes', $post->getCategory()->getName());
    }

     public function testSetAndGetImage()
    {
        $post = new Post();
        $imagePath = 'uploads/images/example.jpg';

        $post->setImage($imagePath);
        $this->assertEquals($imagePath, $post->getImage());
    }

     public function testGetId()
    {
    $category = new Category();

     $reflection = new ReflectionClass(Category::class);
    $property = $reflection->getProperty('id');
    $property->setAccessible(true);
    $property->setValue($category, 123);

    $this->assertEquals(123, $category->getId());
    }

     public function testGetPosts()
    {
    $category = new Category();

    $post1 = $this->createMock(Post::class);
    $post2 = $this->createMock(Post::class);

    $category->addPost($post1);
    $category->addPost($post2);

    $posts = $category->getPosts();

    $this->assertInstanceOf(Collection::class, $posts);

    $this->assertCount(2, $posts);

    $this->assertTrue($posts->contains($post1));
    $this->assertTrue($posts->contains($post2));
    }

      public function testRemovePost()
    {
        $category = new Category();
        $category->setName('Engine Parts');

        $post = new Post();
        $post->setTitle('Test Post');
        $post->setCategory($category);

        $category->addPost($post);

        $this->assertTrue($category->getPosts()->contains($post));
        $this->assertSame($category, $post->getCategory());

        $category->removePost($post);

        $this->assertFalse($category->getPosts()->contains($post));

        $this->assertNull($post->getCategory());
    }


}
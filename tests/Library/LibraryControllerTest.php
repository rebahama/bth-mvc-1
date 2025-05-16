<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Library;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use App\Controller\LibraryController;
use App\Repository\LibraryRepository;
class LibraryControllerTest extends WebTestCase
{
    public function testShowAllLibraryReturnsJson(): void
     /**
     * Test so that the jsonresponse is returned
     */
    {
        $mockRepo = $this->createMock(LibraryRepository::class);
        $mockRepo->method('findAll')->willReturn([
            ['id' => 1, 'title' => 'Test Book']
        ]);

        $controller = new LibraryController();

        $response = $controller->showAllLibrary($mockRepo);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertJson($response->getContent());
    }
    /**
     * Test so that the jsonresponse is returned
     */

    public function testViewAllLibraryReturnsResponse(): void
    {
        $mockRepo = $this->createMock(LibraryRepository::class);
        $mockRepo->method('findAll')->willReturn([
            ['id' => 1, 'title' => 'Test Book']
        ]);

        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->method('render')->willReturn(new Response('Rendered Content'));

        $response = $controller->viewAllLibrary($mockRepo);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('Rendered Content', $response->getContent());
    }

    public function testCreateLibraryPost(): void
    /**
     * Test so that the library object is created with fake data to put in the post.
     * 
     */
    {
        $client = static::createClient();
        $filePath = sys_get_temp_dir() . '/test_image.jpg';
        file_put_contents($filePath, 'dummy content');

        $uploadedFile = new UploadedFile(
            $filePath,
            'test_image.jpg',
            'image/jpeg',
            null,
            true
        );

        $client->request('POST', '/library/create', [
            'title' => 'Test Book',
            'isbn' => '1234567890',
            'author' => 'John Doe',
        ], [
            'image' => $uploadedFile
        ]);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testViewOneLibrary(): void
    /**
     * Test so that one detalied view of the library book is shown.
     */
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $library = new Library();
        $library->setTitle('Test Book');
        $library->setIsbn('TEST123');
        $library->setAuthor('Author Name');
        $library->setImagePath('uploads/test.jpg');

        $entityManager->persist($library);
        $entityManager->flush();

        $libraryId = $library->getId();

        $client->request('GET', '/library/view/' . $libraryId);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('body', 'Test Book');
        $this->assertSelectorTextContains('body', 'Author Name');
    }

    

    public function testUpdateLibrarySubmit(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $library = new Library();
        $library->setTitle('Old Title');
        $library->setIsbn('OLD123');
        $library->setAuthor('Old Author');
        $library->setImagePath('uploads/old.jpg');

        $entityManager->persist($library);
        $entityManager->flush();

        $client->request('POST', '/library/update/' . $library->getId(), [
            'title' => 'New Title',
            'author' => 'New Author',
            'isbn' => 'NEW123',
        ]);

        $this->assertResponseRedirects('/library/view');

        $updatedLibrary = $entityManager->getRepository(Library::class)->find($library->getId());

        $this->assertEquals('New Title', $updatedLibrary->getTitle());
        $this->assertEquals('New Author', $updatedLibrary->getAuthor());
        $this->assertEquals('NEW123', $updatedLibrary->getIsbn());
    }

    public function testIndexPage(): void
    /**
     * Checks that the index page for the library is rendred and shown with the GET request.
     */
    {
        $client = static::createClient();
        $client->request('GET', '/library');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }


    

    
}

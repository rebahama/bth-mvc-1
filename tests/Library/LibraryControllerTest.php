<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Library;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\File\UploadedFile;
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

<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehicleControllerTest extends WebTestCase
{
    // test la création d'un véhicule
    public function testCreateVehicle(): void
    {
        //Log in as an admin
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testAdminUser = $userRepository->findOneByEmail('admin@example.com');

        $client->loginUser($testAdminUser);

        $crawler = $client->request('GET', '/vehicles/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'type' => 'car',
            'marque' => 'TestMarque',
            'model' => 'TestModel',
            'last_maintenance' => '2025-01-01',
            'nb_kilometrage' => 100000,
            'nb_serie' => '123456789',
            'supplier_id' => 1,
            'agency_id' => 1,
            'price_per_day' => 100,
        ]);
        $client->submit($form);
        
        $crawler = $client->request('GET', '/vehicles?brand=TestMarque&sort_km=&agency='); // On vérifie que le véhicule a bien été créé
        $this->assertAnySelectorTextSame('.vehicle-item-title', 'TestMarque - TestModel');
        // $this->assertSelectorTextContains('.vehicle-list', 'TestMarque TestModel');
    }
}

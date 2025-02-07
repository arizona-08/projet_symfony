<?php

namespace App\Command;

use App\Entity\Agency;
use App\Entity\Car;
use App\Entity\Motorcycle;
use App\Entity\Status;
use App\Entity\Supplier;
use App\Entity\User;
use App\Entity\Vehicle;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Initailize the database with some seed data',
)]
class SeedDataCommand extends Command
{
    private $manager;
    private $passwordHasher;
    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->passwordHasher = $passwordHasher;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    

        $users = [];
        $agencies = [];
        $suppliers = [];
        $status = $this->createStatus();

        $this->createUsers($users);
        $this->createAgencies($users, $agencies);
        $this->createSuppliers($suppliers);
        $this->createVehicles($agencies, $suppliers, $status);

        $this->manager->flush();

        $output->writeln('Data seeded successfully. DO NOT RUN THIS COMMAND IF THE DOCKER VOLUMES ARE NOT EMPTY !!!');
        return Command::SUCCESS;
    }

    public function createUsers(array &$users){

        $clientUsers = [];
        $clientVipUsers = [];
        $agencyHeadUsers = [];
        $orderManagerUsers = [];
        $supplierManagerUsers = [];

        $adminUser = ["name" => "Admin", "email" => "admin@example.com", "password" => "password", "roles" => ["ROLE_ADMIN"]];
        $darkAdminUser = ["name" => "DarkAdmin", "email" => "darkadmin@example.com", "password" => "password", "roles" => ["ROLE_DARK_ADMIN"]];

        for($i = 0; $i < 3; $i++){
            $clientUsers[] = [
                'name' => "Client User $i",
                'email' => "client$i@example.com",
                'password' => 'password',
                'roles' => ['ROLE_USER']
            ];

            $clientVipUsers[] = [
                'name' => "Client VIP User $i",
                'email' => "clientvip$i@example.com",
                'password' => 'password',
                'roles' => ['ROLE_VIP']
            ];

            $agencyHeadUsers[] = [
                'name' => "Agency Head User $i",
                'email' => "agencyh$i@example.com",
                'password' => 'password',
                'roles' => ['ROLE_AGENCY_HEAD']
            ];

            $orderManagerUsers[] = [
                'name' => "Order Manager User $i",
                'email' => "orderm$i@example.com",
                'password' => 'password',
                'roles' => ['ROLE_ORDER_MANAGER']
            ];

            $supplierManagerUsers[] = [
                'name' => "Supplier Manager User $i",
                'email' => "supplierm$i@example.com",
                'password' => 'password',
                'roles' => ['ROLE_SUPPLIER_MANAGER']
            ];
        }

        $allusers = array_merge($clientUsers, $clientVipUsers, $agencyHeadUsers, $orderManagerUsers, $supplierManagerUsers, [$adminUser, $darkAdminUser]);

        foreach($allusers as $user){
            $userEntity = new User();
            $userEntity->setName($user['name']);
            $userEntity->setEmail($user['email']);
            $hashedPassword = $this->passwordHasher->hashPassword($userEntity, $user['password']);
            $userEntity->setPassword($hashedPassword);
            $userEntity->setCreatedAt(new DateTimeImmutable());
            $userEntity->setEmailVerifiedAt(new DateTimeImmutable());
            $userEntity->setRoles($user['roles']);

            $users[] = $userEntity;
            $this->manager->persist($userEntity);
        }

        return $allusers;
    }

    public function createAgencies(array &$users, array &$agencies){
        $agencyHeadUsers = [];
        foreach($users as $user){
            if($user->hasRole('ROLE_AGENCY_HEAD')){
                $agencyHeadUsers[] = $user;
            }
        }

        foreach($agencyHeadUsers as $agencyHeadUser){
            $agency = new Agency();
            $agency->setLabel($agencyHeadUser->getName() . "'s Agency");
            $agency->setUser($agencyHeadUser);
            $agency->setAddress("15 rue de l'espoir");
            $agency->setCity("Paris");
            $agency->setZipCode(75000);
            $agencies[] = $agency;
            $this->manager->persist($agency);
        }
    }

    public function createSuppliers(array &$suppliers){
        for($i = 0; $i < 3; $i++){
            $supplier = new Supplier();
            $supplier->setLabel("Supplier $i");
            $supplier->setCreatedAt(new DateTimeImmutable());
            $supplier->setUpdatedAt(new DateTimeImmutable());
            $suppliers[] = $supplier;
            $this->manager->persist($supplier);
        }
    }

    public function createVehicles(array &$agencies, array &$suppliers, Status $status){
        $vehicles = [];

        foreach($agencies as $agency){
            for($i = 0; $i < 7; $i++){
                $car = new Car();
                $car->setAgency($agency);
                $car->setMarque('Marque car' . $i);
                $car->setModel('Model car' . $i);
                $car->setYear('2021');
                $car->setLastMaintenance(new DateTimeImmutable());
                $car->setNbKilometrage(1000);
                $car->setNbSerie(substr(bin2hex(random_bytes(10)), 0, 16));
                $car->setPricePerDay(100);
                $car->setGearBoxType('Automatic');
                $car->setEquipment(['Air Conditioning', 'GPS']);
                $car->setSupplier($suppliers[array_rand($suppliers)]);
                $car->setStatus($status);
                $vehicles[] = $car;
                $this->manager->persist($car);
            }

            for($i = 0; $i < 7; $i++){
                $motorcycle = new Motorcycle();
                $motorcycle->setAgency($agency);
                $motorcycle->setMarque('Marque motorcycle ' . $i);
                $motorcycle->setModel('Model motorcycle' . $i);
                $motorcycle->setYear('2021');
                $motorcycle->setLastMaintenance(new DateTimeImmutable());
                $motorcycle->setNbKilometrage(1000);
                $motorcycle->setNbSerie(substr(bin2hex(random_bytes(10)), 0, 16));
                $motorcycle->setPricePerDay(100);
                $motorcycle->setGearBoxType('Automatic');
                $motorcycle->setEquipment(['Air Conditioning', 'GPS']);
                $motorcycle->setSupplier($suppliers[array_rand($suppliers)]);
                $motorcycle->setStatus($status);
                $vehicles[] = $motorcycle;
                $this->manager->persist($motorcycle);
            }
        }
    }

    public function createStatus(){
        $statusNames = ['Disponible', 'Réservé', 'En réparation', 'Indisponible'];
        $statuses = [];
        foreach($statusNames as $statusName){
            $status = new Status();
            $status->setName($statusName);
            $statuses[] = $status;
            $this->manager->persist($status);
        }

        return $statuses[0];
    }
}

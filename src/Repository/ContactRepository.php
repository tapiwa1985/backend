<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * ContactRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Contact::class);
        $this->manager = $manager;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phoneNumber
     * @return Contact
     */
    public function saveContact(string $firstName, string $lastName, string $email, string $phoneNumber): Contact
    {
        $newContact = new Contact();
        $newContact->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPhoneNumber($phoneNumber);
        $this->manager->persist($newContact);
        $this->manager->flush();
        return $newContact;
    }

    /**
     * @param Contact $contact
     * @return array
     */
    public function toArray(Contact $contact)
    {
        return [
            'id' => (int)$contact->getId(),
            'first_name' => (string)$contact->getFirstName(),
            'last_name' => (string)$contact->getLastName(),
            'email' => (string)$contact->getEmail(),
            'phone_number' => (string)$contact->getPhoneNumber()
        ];
    }
}

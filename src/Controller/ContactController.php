<?php

namespace App\Controller;

use App\Classes\Mailer;
use App\Repository\ContactRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface as MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends ApiController
{
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var MailerInterface
     */
    private $mailerInterface;

    /**
     * ContactController constructor.
     * @param ContactRepository $contactRepository
     * @param MailerInterface $mailerInterface
     */
    public function __construct(ContactRepository $contactRepository, MailerInterface $mailerInterface)
    {
        $this->contactRepository = $contactRepository;
        $this->mailerInterface = $mailerInterface;
    }

    /**
     * @param Request $request
     * @Route("/contacts", methods={"POST"})
     * @return JsonResponse
     * @throws Exception
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $email = $data['email'];
        $phoneNumber = $data['phone_number'];
        if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $contact = $this->contactRepository->saveContact($firstName, $lastName, $email, $phoneNumber);
        $mailer = new Mailer($this->mailerInterface);
        $mailer->sendConfirmationMail($email, $firstName);

        return $this->respondCreated($this->contactRepository->toArray($contact));
    }
}
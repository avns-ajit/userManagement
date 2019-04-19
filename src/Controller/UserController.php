<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\DTO\UserDTO;
use App\Model\UserManagerInterface;


/**
 *  @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * UserController constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @Route("/create")
     * @ParamConverter("userDTO", converter="fos_rest.request_body")
     */
    public function create(UserDTO $userDTO,ValidatorInterface $validator)
    {
        $errors = $validator->validate($userDTO);
        if (count($errors) > 0) {
            return $this->validationFailedResponse($errors);
        }
        $this->userManager->createUser($userDTO);
        $response = new Response(json_encode($userDTO->getName()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function validationFailedResponse($errors)
    {
        $errorsString = (string) $errors;
        $response = new Response($errorsString);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
    }

}
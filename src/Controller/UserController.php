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
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->$userManager = $userManager;
    }
    /**
     *  @Route("/all")
     */
    public function listAction()
    {
        $number = random_int(0, 100);

        $response = new Response(json_encode($number));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/add")
     * @ParamConverter("userDTO", converter="fos_rest.request_body")
     */
    public function add(UserDTO $userDTO,ValidatorInterface $validator)
    {
        $errors = $validator->validate($userDTO);
        if (count($errors) > 0) {
            return $this->validationFailedResponse($errors);
        }
        echo $userDTO;
        $this->userManager->createUser($userDTO);
        $response = new Response(json_encode($userDTO->getName()));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    protected function validationFailedResponse($errors)
    {
        $errorsString = (string) $errors;
        $response = new Response($errorsString);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
    }

}
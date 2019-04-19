<?php


namespace App\Controller;

use App\Response\UserResponse;
use http\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\DTO\UserDTO;
use App\DTO\DeleteUserRequest;
use App\Model\UserManagerInterface;
use App\Response\BaseResponse;
use Symfony\Component\HttpFoundation\JsonResponse;



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
     * @var ValidatorInterface
     */
    private $validator;


    /**
     * UserController constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager,ValidatorInterface $validator)
    {
        $this->userManager = $userManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/create")
     * @ParamConverter("userDTO", converter="fos_rest.request_body")
     */
    public function create(UserDTO $userDTO)
    {
        try{
            $errors = $this->validator->validate($userDTO);
            if (count($errors) > 0) {
                $baseResponse=$this->createBaseResponse($errors);
                return $this->generateResponse($baseResponse);
            }
            $userId=$this->userManager->create($userDTO);
            $userResponse = $this->createUserResponse($userDTO, $userId);
            return $this->generateResponse($userResponse);
        }catch(Exception $exception){
            $baseResponse=$this->createBaseResponse($exception->getMessage());
            return $this->generateResponse($baseResponse);
        }
    }

    /**
     * @Route("/delete")
     * @ParamConverter("deleteUserRequest", converter="fos_rest.request_body")
     */
    public function delete(DeleteUserRequest $deleteUserRequest)
    {
        $errors = $this->validator->validate($deleteUserRequest);
        if (count($errors) > 0) {
            $data="";
            foreach ($errors as $key => $value){
                $data=$data.$value->getMessage().",";
            }
            return $this->validationFailedResponse($data);
        }
        $this->userManager->delete($deleteUserRequest);
        $response = new Response(json_encode($deleteUserRequest->getUser()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    private function generateResponse($response)
    {
        $serializedEntity = $this->container->get('serializer')->serialize($response, 'json');
        $response= new Response($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
    }

    private function createBaseResponse($errors)
    {
        $baseResponse= new BaseResponse();
        $baseResponse->setMessage($errors);
        return $baseResponse;
    }

    /**
     * @param UserDTO $userDTO
     * @param $userId
     * @return UserResponse
     */
    private function createUserResponse(UserDTO $userDTO, $userId): UserResponse
    {
        $userResponse = new UserResponse();
        $userResponse->setUserId($userId);
        $userResponse->setRole($userDTO->getRole());
        $userResponse->setName($userDTO->getName());
        $userResponse->setMessage("User Succesfully Created");
        return $userResponse;
    }


}
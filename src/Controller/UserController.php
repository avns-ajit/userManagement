<?php


namespace App\Controller;

use App\Response\UserResponse;
use App\Util\UserManagementUtility;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\DTO\UserDTO;
use App\DTO\DeleteUserDTO;
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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserManagementUtility
     */
    private $userManagementUtility;


    /**
     * UserController constructor.
     * @param UserManagerInterface $userManager
     * @param ValidatorInterface $validator
     * @param UserManagementUtility $userManagementUtility
     */
    public function __construct(UserManagerInterface $userManager,ValidatorInterface $validator,UserManagementUtility $userManagementUtility)
    {
        $this->userManager = $userManager;
        $this->validator = $validator;
        $this->userManagementUtility = $userManagementUtility;
    }

    /**
     * @Route("/create")
     * @ParamConverter("userDTO", converter="fos_rest.request_body")
     * @param UserDTO $userDTO
     * @return Response
     */
    public function create(UserDTO $userDTO)
    {
        $validationFailures = $this->validator->validate($userDTO);
        if (count($validationFailures) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($validationFailures);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $userId=$this->userManager->create($userDTO);
        $userResponse = $this->createUserResponse($userDTO, $userId);
        return $this->userManagementUtility->generateJsonResponse($userResponse,Response::HTTP_OK);
    }

    /**
     * @Route("/delete")
     * @ParamConverter("deleteUserDTO", converter="fos_rest.request_body")
     * @param DeleteUserDTO $deleteUserDTO
     * @return Response
     */
    public function delete(DeleteUserDTO $deleteUserDTO)
    {
        $validationFailures = $this->validator->validate($deleteUserDTO);
        if (count($validationFailures) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($validationFailures);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $this->userManager->delete($deleteUserDTO);
        $userResponse = $this->generateUserResponse($deleteUserDTO);
        return $this->userManagementUtility->generateJsonResponse($userResponse,Response::HTTP_OK);

    }


    /**
     * @Route("/list/all")
     * @return Response
     */
    public function listUsers()
    {
        $users=$this->userManagementUtility->listUsers();
        return $this->userManagementUtility->generateJsonResponse($users,Response::HTTP_OK);

    }


    /**
     * @param UserDTO $userDTO
     * @param $userId
     * @return UserResponse
     */
    private function createUserResponse(UserDTO $userDTO, $userId): UserResponse
    {
        $userResponse = new UserResponse();
        $userResponse->setUser($userId);
        $userResponse->setRole($userDTO->getRole());
        $userResponse->setName($userDTO->getName());
        $userResponse->setMessage("User Successfully Created");
        return $userResponse;
    }

    /**
     * @param DeleteUserDTO $deleteUserDTO
     * @return UserResponse
     */
    private function generateUserResponse(DeleteUserDTO $deleteUserDTO): UserResponse
    {
        $userResponse = new UserResponse();
        $userResponse->setUser($deleteUserDTO->getUser());
        $userResponse->setMessage("User Successfully Deleted");
        return $userResponse;
    }


}
<?php


namespace App\Controller;

use App\Response\UserResponse;
use App\Util\UserManagementUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\DTO\UserDTO;
use App\DTO\DeleteUserDTO;
use App\Model\UserManagerInterface;
use App\Response\BaseResponse;



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
     */
    public function create(UserDTO $userDTO)
    {
        $errors = $this->validator->validate($userDTO);
        if (count($errors) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($errors);
            return $this->userManagementUtility->generateJsonResponse($baseResponse);
        }
        $userId=$this->userManager->create($userDTO);
        $userResponse = $this->createUserResponse($userDTO, $userId);
        return $this->userManagementUtility->generateJsonResponse($userResponse);
    }

    /**
     * @Route("/delete")
     * @ParamConverter("deleteUserDTO", converter="fos_rest.request_body")
     */
    public function delete(DeleteUserDTO $deleteUserDTO)
    {
        $errors = $this->validator->validate($deleteUserDTO);
        if (count($errors) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($errors);
            return $this->userManagementUtility->generateJsonResponse($baseResponse);
        }
        $this->userManager->delete($deleteUserDTO);
        $userResponse = $this->deleteUserResponse($deleteUserDTO);
        return $this->userManagementUtility->generateJsonResponse($userResponse);

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

    private function deleteUserResponse(DeleteUserDTO $deleteUserDTO): UserResponse
    {
        $userResponse = new UserResponse();
        $userResponse->setUser($deleteUserDTO->getUser());
        $userResponse->setMessage("User Deleted Created");
        return $userResponse;
    }


}
<?php


namespace App\tests;

use App\Controller\UserController;
use App\DTO\DeleteUserDTO;
use App\DTO\UserDTO;
use App\Model\UserManagerInterface;
use App\Response\BaseResponse;
use App\Response\UserResponse;
use App\Util\UserManagementUtility;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserControllerTest extends TestCase
{

    private $userManagerMock;

    private $validatorMock;

    private $userManagementUtilityMock;

    private $userController;


    protected function setUp()
    {
        $this->userManagerMock = $this->getMockBuilder(UserManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userManagementUtilityMock = $this->getMockBuilder(UserManagementUtility::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userController = new UserController($this->userManagerMock, $this->validatorMock, $this->userManagementUtilityMock);
    }


    /** @test */
    public function createUserValidationFailureTest(){

        $userDTO = $this->createUserDTO();
        $validationFailures=array("user not valid");
        $baseResponse = new BaseResponse();
        $this->userManagerMock->expects($this->never())
            ->method('create')
            ->with($userDTO);
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($userDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('createBaseResponse')
            ->with($validationFailures)->willReturn($baseResponse);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($baseResponse,Response::HTTP_BAD_REQUEST)->willReturn("{
    \"message\": \"validation failure\"
}");

        $data=$this->userController->create($userDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function createUserTest(){

        $userDTO = $this->createUserDTO();
        $userResponse = $this->createUserResponse();
        $validationFailures=array();
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($userDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->never())
            ->method('createBaseResponse')
            ->with($validationFailures);
        $this->userManagerMock->expects($this->once())
            ->method('create')
            ->with($userDTO)->willReturn("d324ea-6146-11e9-ad9d-24a074f0655e");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($userResponse,Response::HTTP_OK)->willReturn("{
    \"user\": \"d324ea-6146-11e9-ad9d-24a074f0655e\",
    \"initiator\": \"49d324ea-6146-11e9-ad9d-24a074f0655e\"
}");
        $data=$this->userController->create($userDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function deleteUserTest(){

        $deleteUserDTO = $this->createDeleteUserDTO();
        $userResponse = $this->generateUserResponse();
        $validationFailures=array();
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($deleteUserDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->never())
            ->method('createBaseResponse')
            ->with($validationFailures);
        $this->userManagerMock->expects($this->once())
            ->method('delete')
            ->with($deleteUserDTO);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($userResponse,Response::HTTP_OK)->willReturn("{
    \"user\": \"d324ea-6146-11e9-ad9d-24a074f0655e\",
    \"initiator\": \"49d324ea-6146-11e9-ad9d-24a074f0655e\"
}");
        $this->userController = new UserController($this->userManagerMock, $this->validatorMock, $this->userManagementUtilityMock);
        $data=$this->userController->delete($deleteUserDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function deleteUserValidationFailureTest(){

        $deleteUserDTO = $this->createDeleteUserDTO();
        $validationFailures=array("user not valid");
        $baseResponse = new BaseResponse();
        $this->userManagerMock->expects($this->never())
            ->method('delete')
            ->with($deleteUserDTO);
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($deleteUserDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('createBaseResponse')
            ->with($validationFailures)->willReturn($baseResponse);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($baseResponse,Response::HTTP_BAD_REQUEST)->willReturn("{
    \"message\": \"validation failure\"
}");
        $this->userController = new UserController($this->userManagerMock, $this->validatorMock, $this->userManagementUtilityMock);
        $data=$this->userController->delete($deleteUserDTO);
        $this->assertNotNull($data);
    }

    /**
     * @return UserDTO
     */
    private function createUserDTO(): UserDTO
    {
        $userDTO = new UserDTO();
        $userDTO->setName("goku");
        $userDTO->setRole("USER");
        $userDTO->setInitiator("fdd86298-634e-11e9-89aa-24a074f0655");
        return $userDTO;
    }

    private function createUserResponse(): UserResponse
    {
        $userResponse = new UserResponse();
        $userResponse->setName("goku");
        $userResponse->setRole("USER");
        $userResponse->setUser("d324ea-6146-11e9-ad9d-24a074f0655e");
        $userResponse->setMessage("User Successfully Created");
        return $userResponse;
    }

    private function createDeleteUserDTO(): DeleteUserDTO
    {
        $deleteUserDTO = new DeleteUserDTO();
        $deleteUserDTO->setUser("d324ea-6146-11e9-ad9d-24a074f0655e");
        $deleteUserDTO->setInitiator("fdd86298-634e-11e9-89aa-24a074f0655");
        return $deleteUserDTO;
    }

    private function generateUserResponse(): UserResponse
    {
        $userResponse = new UserResponse();
        $userResponse->setUser("d324ea-6146-11e9-ad9d-24a074f0655e");
        $userResponse->setMessage("User Successfully Deleted");
        return $userResponse;
    }
}
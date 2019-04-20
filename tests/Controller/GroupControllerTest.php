<?php


namespace App\Tests\Controller;


use App\DTO\DeleteGroupDTO;
use App\DTO\GroupDTO;
use App\DTO\UserGroupDTO;
use App\Model\GroupManagerInterface;
use App\Response\GroupResponse;
use App\Response\BaseResponse;
use App\Response\UserGroupResponse;
use App\Util\UserManagementUtility;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Controller\GroupController;
use Symfony\Component\HttpFoundation\Response;


class GroupControllerTest extends TestCase
{

    private $groupManagerMock;

    private $validatorMock;

    private $userManagementUtilityMock;

    private $groupController;

    protected function setUp()
    {
        $this->groupManagerMock = $this->getMockBuilder(GroupManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userManagementUtilityMock = $this->getMockBuilder(UserManagementUtility::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->groupController = new GroupController($this->groupManagerMock, $this->validatorMock, $this->userManagementUtilityMock);

    }


    /** @test */
    public function creatGroupValidationFailureTest(){

        $groupDTO = $this->createGroupDTO();
        $validationFailures=array("group not valid");
        $baseResponse = new BaseResponse();
        $this->groupManagerMock->expects($this->never())
            ->method('createGroup')
            ->with($groupDTO);
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($groupDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('createBaseResponse')
            ->with($validationFailures)->willReturn($baseResponse);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($baseResponse,Response::HTTP_BAD_REQUEST)->willReturn("{
    \"message\": \"validation failure\"
}");
        $data=$this->groupController->create($groupDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function createGroupTest(){

        $groupDTO = $this->createGroupDTO();
        $groupResponse = $this->createGroupResponse();
        $validationFailures=array();
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($groupDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->never())
            ->method('createBaseResponse')
            ->with($validationFailures);
        $this->groupManagerMock->expects($this->once())
            ->method('createGroup')
            ->with($groupDTO)->willReturn("d324ea-6146-11e9-ad9d-24a074f0655e");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($groupResponse,Response::HTTP_OK)->willReturn("{
    \"group\": \"d324ea-6146-11e9-ad9d-24a074f0655e\",
    \"initiator\": \"49d324ea-6146-11e9-ad9d-24a074f0655e\"
}");
        $data=$this->groupController->create($groupDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function deleteGroupTest(){

        $deleteUserDTO = $this->createDeleteGroupDTO();
        $groupResponse = $this->generateGroupResponse();
        $validationFailures=array();
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($deleteUserDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->never())
            ->method('createBaseResponse')
            ->with($validationFailures);
        $this->groupManagerMock->expects($this->once())
            ->method('deleteGroup')
            ->with($deleteUserDTO);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($groupResponse,Response::HTTP_OK)->willReturn("{
    \"group\": \"d324ea-6146-11e9-ad9d-24a074f0655e\",
    \"initiator\": \"49d324ea-6146-11e9-ad9d-24a074f0655e\"
}");
        $data=$this->groupController->delete($deleteUserDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function deleteGroupValidationFailureTest(){

        $deleteUserDTO = $this->createDeleteGroupDTO();
        $validationFailures=array("group not valid");
        $baseResponse = new BaseResponse();
        $this->groupManagerMock->expects($this->never())
            ->method('deleteGroup')
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
        $data=$this->groupController->delete($deleteUserDTO);
        $this->assertNotNull($data);
    }


    /** @test */
    public function addUserGroupTest(){

        $userGroupDTO = $this->createUserGroupDTO();
        $userGroupResponse = $this->createUserGroupResponse();
        $validationFailures=array();
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($userGroupDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->never())
            ->method('createBaseResponse')
            ->with($validationFailures);
        $this->groupManagerMock->expects($this->once())
            ->method('addToGroup')
            ->with($userGroupDTO);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($userGroupResponse,Response::HTTP_OK)->willReturn("{
    \"group\": \"d324ea-6146-11e9-ad9d-24a074f0655e\",
    \"initiator\": \"49d324ea-6146-11e9-ad9d-24a074f0655e\"
}");
        $data=$this->groupController->add($userGroupDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function addUserGroupValidationFailureTest(){

        $userGroupDTO = $this->createUserGroupDTO();
        $validationFailures=array("group/user not valid");
        $baseResponse = new BaseResponse();
        $this->groupManagerMock->expects($this->never())
            ->method('addToGroup')
            ->with($userGroupDTO);
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($userGroupDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('createBaseResponse')
            ->with($validationFailures)->willReturn($baseResponse);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($baseResponse,Response::HTTP_BAD_REQUEST)->willReturn("{
    \"message\": \"validation failure\"
}");
        $data=$this->groupController->add($userGroupDTO);
        $this->assertNotNull($data);
    }


    /** @test */
    public function removeUserGroupTest(){

        $userGroupDTO = $this->createUserGroupDTO();
        $userGroupResponse = $this->createUserGroupResponse();
        $validationFailures=array();
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($userGroupDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->never())
            ->method('createBaseResponse')
            ->with($validationFailures);
        $this->groupManagerMock->expects($this->once())
            ->method('removeFromGroup')
            ->with($userGroupDTO);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($userGroupResponse,Response::HTTP_OK)->willReturn("{
    \"group\": \"d324ea-6146-11e9-ad9d-24a074f0655e\",
    \"initiator\": \"49d324ea-6146-11e9-ad9d-24a074f0655e\"
}");
        $data=$this->groupController->remove($userGroupDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function removeUserGroupValidationFailureTest(){

        $userGroupDTO = $this->createUserGroupDTO();
        $validationFailures=array("group/user not valid");
        $baseResponse = new BaseResponse();
        $this->groupManagerMock->expects($this->never())
            ->method('removeFromGroup')
            ->with($userGroupDTO);
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with($userGroupDTO)->willReturn($validationFailures);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('createBaseResponse')
            ->with($validationFailures)->willReturn($baseResponse);
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateJsonResponse')
            ->with($baseResponse,Response::HTTP_BAD_REQUEST)->willReturn("{
    \"message\": \"validation failure\"
}");
        $data=$this->groupController->remove($userGroupDTO);
        $this->assertNotNull($data);
    }


    private function createGroupDTO(): GroupDTO
    {
        $groupDTO = new GroupDTO();
        $groupDTO->setName("vegeta");
        $groupDTO->setInitiator("fdd86298-634e-11e9-89aa-24a074f0655");
        return $groupDTO;
    }

    private function createGroupResponse(): GroupResponse
    {
        $groupResponse = new GroupResponse();
        $groupResponse->setGroup("d324ea-6146-11e9-ad9d-24a074f0655e");
        $groupResponse->setName("vegeta");
        $groupResponse->setMessage("Group Successfully Created");
        return $groupResponse;
    }


    private function generateGroupResponse(): GroupResponse
    {
        $groupResponse = new GroupResponse();
        $groupResponse->setGroup("d324ea-6146-11e9-ad9d-24a074f0655e");
        $groupResponse->setMessage("Group Successfully Deleted");
        return $groupResponse;
    }

    private function createDeleteGroupDTO(): DeleteGroupDTO
    {
        $deleteGroupDTO = new DeleteGroupDTO();
        $deleteGroupDTO->setGroup("d324ea-6146-11e9-ad9d-24a074f0655e");
        $deleteGroupDTO->setInitiator("fdd86298-634e-11e9-89aa-24a074f0655");
        return $deleteGroupDTO;
    }

    private function createUserGroupDTO(): UserGroupDTO
    {
        $userGroupDTO = new UserGroupDTO();
        $userGroupDTO->setGroup("d324ea-6146-11e9-ad9d-24a074f0655e");
        $userGroupDTO->setInitiator("fdd86298-634e-11e9-89aa-24a074f0655");
        $userGroupDTO->setUser("c324ea-6146-11e9-ad9d-24a074f0655e");
        return $userGroupDTO;
    }

    private function createUserGroupResponse(): UserGroupResponse
    {
        $userGroupResponse = new UserGroupResponse();
        $userGroupResponse->setGroup("d324ea-6146-11e9-ad9d-24a074f0655e");
        $userGroupResponse->setUser("c324ea-6146-11e9-ad9d-24a074f0655e");
        $userGroupResponse->setMessage("Group Action Successfully");
        return $userGroupResponse;
    }




}
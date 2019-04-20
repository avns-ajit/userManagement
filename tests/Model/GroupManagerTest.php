<?php


namespace App\Tests\Model;


use App\Constant\UserManagementConstants;
use App\DTO\DeleteGroupDTO;
use App\DTO\DeleteUserDTO;
use App\DTO\GroupDTO;
use App\DTO\UserDTO;
use App\DTO\UserGroupDTO;
use App\Entity\Group;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserGroup;
use App\Exception\UserManagementException;
use App\Model\GroupManager;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Repository\UserGroupRepository;
use App\Util\UserManagementUtility;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class GroupManagerTest extends TestCase
{
    private $groupRepositoryMock;

    private $userRepositoryMock;

    private $userGroupRepositoryMock;

    private $userManagementUtilityMock;

    private $groupManager;

    protected function setUp()
    {
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->groupRepositoryMock = $this->getMockBuilder(GroupRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userManagementUtilityMock = $this->getMockBuilder(UserManagementUtility::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userGroupRepositoryMock = $this->getMockBuilder(UserGroupRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->groupManager = new GroupManager($this->groupRepositoryMock, $this->userManagementUtilityMock,$this->userRepositoryMock, $this->userGroupRepositoryMock);

    }


    /** @test */
    public function createGroupTest(){
        $groupDTO = $this->createGroupDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with("GROUP","CREATE")->willReturn("GROUP_CREATE");
        $permission = new Permission();
        $permission->setName("GROUP_CREATE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($groupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('save')
            ->with(self::anything());
        $data=$this->groupManager->createGroup($groupDTO);
        $this->assertTrue($data instanceof Uuid);
    }

    /** @test
     */
    public function createGroupInValidPermissionTest(){

        $groupDTO = $this->createGroupDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with("GROUP","CREATE")->willReturn("GROUP_CREATE");
        $permission = new Permission();
        $permission->setName("USER_CREATE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($groupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->never())
            ->method('save')
            ->with(self::anything());
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Initiator not authorized to perform this action");
        $this->groupManager->createGroup($groupDTO);
    }

    /** @test */
    public function deleteGroupTest(){
        $group=new Group();
        $deleteGroupDTO = $this->createDeleteGroupDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"DELETE")->willReturn("GROUP_DELETE");
        $permission = new Permission();
        $permission->setName("GROUP_DELETE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($deleteGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($deleteGroupDTO->getGroup())->willReturn($group);
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkUsersInGroup')
            ->with($deleteGroupDTO->getGroup());
        $this->groupRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($group);
        $data=$this->groupManager->deleteGroup($deleteGroupDTO);
        $this->assertNull($data);
    }

    /** @test */
    public function deleteGroupInValidPermissionTest(){
        $group=new Group();
        $deleteGroupDTO = $this->createDeleteGroupDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"DELETE")->willReturn("GROUP_DELETE");
        $permission = new Permission();
        $permission->setName("USER_DELETE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($deleteGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($deleteGroupDTO->getGroup())->willReturn($group);
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkUsersInGroup')
            ->with($deleteGroupDTO->getGroup());
        $this->groupRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($group);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Initiator not authorized to perform this action");
        $data=$this->groupManager->deleteGroup($deleteGroupDTO);
    }

    /** @test */
    public function deleteInValidGroupTest(){
        $group=new Group();
        $deleteGroupDTO = $this->createDeleteGroupDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"DELETE")->willReturn("GROUP_DELETE");
        $permission = new Permission();
        $permission->setName("USER_DELETE");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($deleteGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($deleteGroupDTO->getGroup())->willThrowException(new UserManagementException(UserManagementConstants::GROUP_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST));
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('checkUsersInGroup')
            ->with($deleteGroupDTO->getGroup());
        $this->groupRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($group);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided group is not present, Please check available groups");
        $this->groupManager->deleteGroup($deleteGroupDTO);
    }

    /** @test */
    public function deleteGroupWithUsersTest(){
        $group=new Group();
        $deleteGroupDTO = $this->createDeleteGroupDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"DELETE")->willReturn("GROUP_DELETE");
        $permission = new Permission();
        $permission->setName("GROUP_DELETE");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($deleteGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($deleteGroupDTO->getGroup())->willReturn($group);
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkUsersInGroup')
            ->with($deleteGroupDTO->getGroup())->willThrowException(new UserManagementException(UserManagementConstants::GROUP_NOT_EMPTY,Response::HTTP_FORBIDDEN));
        $this->groupRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($group);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided Group contains users,remove users from group to perform this action");
        $this->groupManager->deleteGroup($deleteGroupDTO);
    }


    /** @test */
    public function addToGroupTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"ADD")->willReturn("GROUP_ADD");
        $permission = new Permission();
        $permission->setName("GROUP_ADD");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup());
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser());
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkIfGroupAssigned')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('save')
            ->with(self::anything());
        $data=$this->groupManager->addToGroup($userGroupDTO);
        $this->assertNull($data);
    }

    /** @test */
    public function addToGroupInvalidUserTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"ADD")->willReturn("GROUP_ADD");
        $permission = new Permission();
        $permission->setName("GROUP_ADD");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->never())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup());
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser())->willThrowException(new UserManagementException(UserManagementConstants::USER_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST));
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('checkIfGroupAssigned')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('save')
            ->with($userGroupDTO);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided user is not present, Please check available users");
        $this->groupManager->addToGroup($userGroupDTO);
    }

    /** @test */
    public function addToGroupInvalidPermissionTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"ADD")->willReturn("GROUP_ADD");
        $permission = new Permission();
        $permission->setName("GROUP_DELETE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup());
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser());
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkIfGroupAssigned')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('save')
            ->with($userGroupDTO);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Initiator not authorized to perform this action");
        $this->groupManager->addToGroup($userGroupDTO);
    }

    /** @test */
    public function addToInvalidGroupTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"ADD")->willReturn("GROUP_ADD");
        $permission = new Permission();
        $permission->setName("GROUP_ADD");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup())->willThrowException(new UserManagementException(UserManagementConstants::GROUP_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST));;
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser());
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('checkIfGroupAssigned')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('save')
            ->with($userGroupDTO);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided group is not present, Please check available groups");
        $this->groupManager->addToGroup($userGroupDTO);
    }

    /** @test */
    public function addToAlreadyAssignedGroupTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"ADD")->willReturn("GROUP_ADD");
        $permission = new Permission();
        $permission->setName("GROUP_ADD");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup());
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser());
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkIfGroupAssigned')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup())->willThrowException(new UserManagementException(UserManagementConstants::GROUP_ALREADY_ASSIGNED,Response::HTTP_BAD_REQUEST));
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('save')
            ->with($userGroupDTO);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided group is already assigned to user");
        $this->groupManager->addToGroup($userGroupDTO);
    }

    /** @test */
    public function removeFromGroupTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"REMOVE")->willReturn("GROUP_REMOVE");
        $permission = new Permission();
        $permission->setName("GROUP_REMOVE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup());
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser());
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkIfGroupHasUser')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('delete')
            ->with(self::anything());
        $data=$this->groupManager->removeFromGroup($userGroupDTO);
        $this->assertNull($data);
    }

    /** @test */
    public function removeFromGroupInvalidUserTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"REMOVE")->willReturn("GROUP_REMOVE");
        $permission = new Permission();
        $permission->setName("GROUP_REMOVE");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->never())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup());
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser())->willThrowException(new UserManagementException(UserManagementConstants::USER_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST));
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('checkIfGroupHasUser')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($userGroupDTO);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided user is not present, Please check available users");
        $this->groupManager->removeFromGroup($userGroupDTO);
    }

    /** @test */
    public function removeFromGroupInvalidPermissionTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"REMOVE")->willReturn("GROUP_REMOVE");
        $permission = new Permission();
        $permission->setName("GROUP_DELETE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup());
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser());
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkIfGroupHasUser')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($userGroupDTO);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Initiator not authorized to perform this action");
        $this->groupManager->removeFromGroup($userGroupDTO);
    }

    /** @test */
    public function removeFromInvalidGroupTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"REMOVE")->willReturn("GROUP_REMOVE");
        $permission = new Permission();
        $permission->setName("GROUP_REMOVE");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup())->willThrowException(new UserManagementException(UserManagementConstants::GROUP_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST));;
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser());
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('checkIfGroupHasUser')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($userGroupDTO);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided group is not present, Please check available groups");
        $this->groupManager->removeFromGroup($userGroupDTO);
    }

    /** @test */
    public function removeFromAlreadyRemovedGroupTest(){
        $userGroupDTO = $this->createUserGroupDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"REMOVE")->willReturn("GROUP_REMOVE");
        $permission = new Permission();
        $permission->setName("GROUP_REMOVE");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($userGroupDTO->getInitiator())->willReturn(Array($permission));
        $this->groupRepositoryMock->expects($this->once())
            ->method('checkGroup')
            ->with($userGroupDTO->getGroup());
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($userGroupDTO->getUser());
        $this->userGroupRepositoryMock->expects($this->once())
            ->method('checkIfGroupHasUser')
            ->with($userGroupDTO->getUser(),$userGroupDTO->getGroup())->willThrowException(new UserManagementException(UserManagementConstants::GROUP_NOT_ASSIGNED,Response::HTTP_BAD_REQUEST));
        $this->userGroupRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($userGroupDTO);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided group is not assigned to user");
        $this->groupManager->removeFromGroup($userGroupDTO);
    }



    /**
     * @return UserDTO
     */
    private function createGroupDTO(): GroupDTO
    {
        $groupDTO = new GroupDTO();
        $groupDTO->setName("goku");
        $groupDTO->setInitiator("fdd86298-634e-11e9-89aa-24a074f0655");
        return $groupDTO;
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
        $userGroupDTO->setUser("abcd-6146-11e9-ad9d-24a074f0655e");
        $userGroupDTO->setInitiator("fdd86298-634e-11e9-89aa-24a074f0655");
        return $userGroupDTO;
    }

}
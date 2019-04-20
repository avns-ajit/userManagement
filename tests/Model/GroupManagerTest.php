<?php


namespace App\Tests\Model;


use App\Constant\UserManagementConstants;
use App\DTO\DeleteGroupDTO;
use App\DTO\DeleteUserDTO;
use App\DTO\GroupDTO;
use App\DTO\UserDTO;
use App\Entity\Group;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;
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
            ->with(self::anything(),"DELETE")->willReturn("USER_DELETE");
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
        $this->groupRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($group);
        $data=$this->groupManager->deleteGroup($deleteGroupDTO);
        $this->assertNull($data);
    }

//    /** @test */
//    public function deleteInValidPermissionTest(){
//        $user=new User();
//        $role= new Role();
//        $role->setName("USER");
//        $user->setRoles(array($role));
//        $deleteUserDTO = $this->createDeleteUserDTO();
//        $this->userManagementUtilityMock->expects($this->once())
//            ->method('generateInitiatorAction')
//            ->with(self::anything(),"DELETE")->willReturn("USER_DELETE");
//        $permission = new Permission();
//        $permission->setName("USER_CREATE");
//        $this->userManagementUtilityMock->expects($this->once())
//            ->method('checkPermissions')
//            ->with($deleteUserDTO->getInitiator())->willReturn(Array($permission));
//        $this->userRepositoryMock->expects($this->once())
//            ->method('checkUser')
//            ->with($deleteUserDTO->getUser())->willReturn($user);
//        $this->userRepositoryMock->expects($this->never())
//            ->method('delete')
//            ->with($user);
//        $this->expectException(UserManagementException::class);
//        $this->expectExceptionMessage("Initiator not authorized to perform this action");
//        $data=$this->userManager->delete($deleteUserDTO);
//    }
//
//    /** @test */
//    public function deleteInValidUserTest(){
//        $user=new User();
//        $deleteUserDTO = $this->createDeleteUserDTO();
//        $this->userManagementUtilityMock->expects($this->never())
//            ->method('generateInitiatorAction')
//            ->with(self::anything(),"DELETE")->willReturn("USER_DELETE");
//        $permission = new Permission();
//        $permission->setName("USER_CREATE");
//        $this->userManagementUtilityMock->expects($this->never())
//            ->method('checkPermissions')
//            ->with($deleteUserDTO->getInitiator())->willReturn(Array($permission));
//        $this->userRepositoryMock->expects($this->once())
//            ->method('checkUser')
//            ->with($deleteUserDTO->getUser())->willThrowException( new UserManagementException(UserManagementConstants::USER_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST));
//        $this->userRepositoryMock->expects($this->never())
//            ->method('delete')
//            ->with($user);
//        $this->expectException(UserManagementException::class);
//        $this->expectExceptionMessage("Provided user is not present, Please check available users");
//        $this->userManager->delete($deleteUserDTO);
//    }


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

}
<?php


namespace App\Tests\Model;

use App\Constant\UserManagementConstants;
use App\DTO\DeleteUserDTO;
use App\DTO\UserDTO;
use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;
use App\Exception\UserManagementException;
use App\Model\UserManager;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use App\Util\UserManagementUtility;
use PHPUnit\Framework\TestCase;
use App\Response\BaseResponse;
use Symfony\Component\HttpFoundation\Response;
use Ramsey\Uuid\Uuid;

class UserManagerTest extends TestCase
{
    private $userRepositoryMock;

    private $roleRepositoryMock;

    private $userManagementUtilityMock;

    private $userRoleRepositoryMock;

    private $userManager;

    protected function setUp()
    {
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->roleRepositoryMock = $this->getMockBuilder(RoleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userManagementUtilityMock = $this->getMockBuilder(UserManagementUtility::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userRoleRepositoryMock = $this->getMockBuilder(UserRoleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userManager = new UserManager($this->userRepositoryMock, $this->roleRepositoryMock,$this->userRoleRepositoryMock, $this->userManagementUtilityMock);

    }


    /** @test */
    public function createTest(){
        $userDTO = $this->createUserDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with($userDTO->getRole(),"CREATE")->willReturn("USER_CREATE");
        $permission = new Permission();
        $permission->setName("USER_CREATE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($userDTO->getInitiator())->willReturn(Array($permission));
        $this->roleRepositoryMock->expects($this->once())
            ->method('checkRole')
            ->with($userDTO->getRole())->willReturn(new Role());
        $this->userRepositoryMock->expects($this->once())
            ->method('save')
            ->with(self::anything());
        $this->userRoleRepositoryMock->expects($this->once())
            ->method('save')
            ->with(self::anything());
        $data=$this->userManager->create($userDTO);
        $this->assertTrue($data instanceof Uuid);
    }

    /** @test
     */
    public function createInValidPermissionTest(){

        $userDTO = $this->createUserDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with($userDTO->getRole(),"CREATE")->willReturn("USER_CREATE");
        $permission = new Permission();
        $permission->setName("USER_DELETE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($userDTO->getInitiator())->willReturn(Array($permission));
        $this->roleRepositoryMock->expects($this->never())
            ->method('checkRole')
            ->with($userDTO->getRole())->willReturn(new Role());
        $this->userRepositoryMock->expects($this->never())
            ->method('save')
            ->with(self::anything());
        $this->userRoleRepositoryMock->expects($this->never())
            ->method('save')
            ->with(self::anything());
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Initiator not authorized to perform this action");
        $this->userManager->create($userDTO);
    }

    /** @test */
    public function deleteTest(){
        $user=new User();
        $role= new Role();
        $role->setName("USER");
        $user->setRoles(array($role));
        $deleteUserDTO = $this->createDeleteUserDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"DELETE")->willReturn("USER_DELETE");
        $permission = new Permission();
        $permission->setName("USER_DELETE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($deleteUserDTO->getInitiator())->willReturn(Array($permission));
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($deleteUserDTO->getUser())->willReturn($user);
        $this->userRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($user);
        $data=$this->userManager->delete($deleteUserDTO);
        $this->assertNotNull($data);
    }

    /** @test */
    public function deleteInValidPermissionTest(){
        $user=new User();
        $role= new Role();
        $role->setName("USER");
        $user->setRoles(array($role));
        $deleteUserDTO = $this->createDeleteUserDTO();
        $this->userManagementUtilityMock->expects($this->once())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"DELETE")->willReturn("USER_DELETE");
        $permission = new Permission();
        $permission->setName("USER_CREATE");
        $this->userManagementUtilityMock->expects($this->once())
            ->method('checkPermissions')
            ->with($deleteUserDTO->getInitiator())->willReturn(Array($permission));
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($deleteUserDTO->getUser())->willReturn($user);
        $this->userRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($user);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Initiator not authorized to perform this action");
        $data=$this->userManager->delete($deleteUserDTO);
    }

    /** @test */
    public function deleteInValidUserTest(){
        $user=new User();
        $deleteUserDTO = $this->createDeleteUserDTO();
        $this->userManagementUtilityMock->expects($this->never())
            ->method('generateInitiatorAction')
            ->with(self::anything(),"DELETE")->willReturn("USER_DELETE");
        $permission = new Permission();
        $permission->setName("USER_CREATE");
        $this->userManagementUtilityMock->expects($this->never())
            ->method('checkPermissions')
            ->with($deleteUserDTO->getInitiator())->willReturn(Array($permission));
        $this->userRepositoryMock->expects($this->once())
            ->method('checkUser')
            ->with($deleteUserDTO->getUser())->willThrowException( new UserManagementException(UserManagementConstants::USER_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST));
        $this->userRepositoryMock->expects($this->never())
            ->method('delete')
            ->with($user);
        $this->expectException(UserManagementException::class);
        $this->expectExceptionMessage("Provided user is not present, Please check available users");
        $this->userManager->delete($deleteUserDTO);
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

    private function createDeleteUserDTO(): DeleteUserDTO
    {
        $deleteUserDTO = new DeleteUserDTO();
        $deleteUserDTO->setUser("d324ea-6146-11e9-ad9d-24a074f0655e");
        $deleteUserDTO->setInitiator("fdd86298-634e-11e9-89aa-24a074f0655");
        return $deleteUserDTO;
    }


}
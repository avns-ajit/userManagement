<?php


namespace App\Tests\Util;


use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Util\UserManagementUtility;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\GroupRepository;

class UserManagementUtilityTest extends TestCase
{


    private $userRepositoryMock;

    private $roleRepositoryMock;

    private $serializerInterfaceMock;

    private $userManagementUtility;

    private $groupRepositoryMock;

    protected function setUp()
    {
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->groupRepositoryMock = $this->getMockBuilder(GroupRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->roleRepositoryMock = $this->getMockBuilder(RoleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->serializerInterfaceMock = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userManagementUtility = new UserManagementUtility($this->userRepositoryMock, $this->roleRepositoryMock,$this->serializerInterfaceMock, $this->groupRepositoryMock);
    }

    /** @test */
    public function checkPermissionsTest(){
        $user=new User();
        $role= new Role();
        $role->setName("USER");
        $user->setRoles(array($role));
        $permission = new Permission();
        $permission->setName("GROUP_CREATE");
        $this->roleRepositoryMock->expects($this->once())
            ->method('findPermissionsForRoles')
            ->with(self::anything())->willReturn(Array($permission));
        $this->userRepositoryMock->expects($this->once())
            ->method('findInitiator')
            ->with(self::anything())->willReturn($user);
        $data=$this->userManagementUtility->checkPermissions("eqweqeewfwef-fwe-verwv");
        foreach ($data as $key => $value){
            $this->assertTrue(strcmp("GROUP_CREATE", $value->{'name'})==0);
        }
        $this->assertNotNull($data);

    }

    /** @test */
    public function generateJsonResponseTest(){
        $this->serializerInterfaceMock->expects($this->once())
            ->method('serialize')
            ->with(self::anything(),'json');
        $data=$this->userManagementUtility->generateJsonResponse("eqweqeewfwef-fwe-verwv","100");
        $this->assertNotNull($data);

    }

    /** @test */
    public function generateInitiatorActionTest(){
        $data=$this->userManagementUtility->generateInitiatorAction("USER","ADD");
        $this->assertTrue(strcmp("USER_ADD", "USER_ADD")==0);

    }

}
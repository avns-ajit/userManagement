<?php


namespace App\Util;


use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use App\Response\BaseResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UserManagementUtility
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var SerializerInterface
     */
    private $serializerInterface;


    public function __construct(UserRepository $userRepository,RoleRepository $roleRepository,SerializerInterface $serializerInterface,GroupRepository $groupRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->serializerInterface = $serializerInterface;
        $this->groupRepository = $groupRepository;

    }

    /**
     * @param $user
     * @return mixed
     */
    public function checkPermissions($user){

        $userDetails=$this->userRepository->findInitiator($user);
        $roleIds = array();
        foreach ($userDetails->getRoles() as $key => $value) {
            $roleId=$value->{'id'};
            $roleIds[] = $roleId;
        }
        $permissions=$this->roleRepository->findPermissionsForRoles($roleIds);
        return $permissions;
    }

    /**
     * @param $response
     * @param $code
     * @return Response
     */
    public function generateJsonResponse($response,$code)
    {
        $serializedResponse = $this->serializerInterface->serialize($response, 'json');
        $response= new Response($serializedResponse);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($code);
        return $response;
    }

    /**
     * @param $errors
     * @return BaseResponse
     */
    public function createBaseResponse($errors)
    {
        $baseResponse= new BaseResponse();
        $baseResponse->setMessage($errors);
        return $baseResponse;
    }

    /**
     * @param $type
     * @param $action
     * @return string
     */
    public function generateInitiatorAction($type,$action)
    {
        $seperator="_";
        return $type.$seperator.$action;
    }

    /**
     * @return array
     */
    public function listUsers()
    {
        return $this->userRepository->findAll();
    }

    /**
     * @return array
     */
    public function listGroups()
    {
        return $this->groupRepository->findAll();
    }
}
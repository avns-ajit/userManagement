<?php


namespace App\Util;


use App\Repository\RoleRepository;
use App\Repository\UserRepository;
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
     * @var RoleRespository
     */
    private $roleRespository;

    /**
     * @var SerializerInterface
     */
    private $serializerInterface;


    public function __construct(UserRepository $userRepository,RoleRepository $roleRepository,SerializerInterface $serializerInterface)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->serializerInterface = $serializerInterface;

    }

    /**
     * @param $user
     * @return mixed
     */
    public function getUserPermissions($user){

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
     * @return Response
     */
    public function generateJsonResponse($response,$code)
    {
        $serializedEntity = $this->serializerInterface->serialize($response, 'json');
        $response= new Response($serializedEntity);
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


    public function generateInitiatorAction($type,$action)
    {
        $seperator="_";
        return $type.$seperator.$action;
    }
}
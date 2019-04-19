<?php


namespace App\Util;


use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Response\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

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


    public function __construct(UserRepository $userRepository,RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;

    }

    /**
     * @param $user
     * @return mixed
     */
    public function getUserPermissions($user){

        $userDetails=$this->userRepository->findByUser($user);
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
    public function generateJsonResponse($response)
    {
        $serializedEntity = $this->container->get('serializer')->serialize($response, 'json');
        $response= new Response($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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


    public function generateInitiatorAction($role,$action)
    {
        $seperator="_";
        return $role.$seperator.$action;
    }
}
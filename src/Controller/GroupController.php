<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Model\GroupManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\DTO\GroupDTO;


/**
 *  @Route("/group")
 */
class GroupController extends AbstractController
{

    /**
     * @var GroupManagerInterface
     */
    private $groupManager;

    /**
     * GroupController constructor.
     * @param GroupManagerInterface $groupManager
     */
    public function __construct(GroupManagerInterface $groupManager)
    {
        $this->groupManager = $groupManager;
    }

    /**
     * @Route("/create")
     * @ParamConverter("groupDTO", converter="fos_rest.request_body")
     */
    public function create(GroupDTO $groupDTO,ValidatorInterface $validator)
    {
        $errors = $validator->validate($groupDTO);
        if (count($errors) > 0) {
            return $this->validationFailedResponse($errors);
        }
        $this->groupManager->createGroup($groupDTO);
        $response = new Response(json_encode($groupDTO->getName()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function validationFailedResponse($errors)
    {
        $errorsString = (string) $errors;
        $response = new Response($errorsString);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
    }

}
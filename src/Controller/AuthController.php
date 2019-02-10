<?php
namespace  App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 * @Route("/api")
 * Class AuthController
 * @package App\Controller
 */
class AuthController extends AbstractController
{
    /**
     * @Route("/register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * @Route("/logged-user")
     * @return Response
     */
    public function api()
    {

        if (!$this->getUser()) {
            throw new \Exception('NO User');
        }
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }
}
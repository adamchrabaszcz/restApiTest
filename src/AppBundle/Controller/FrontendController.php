<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Model\{User, Item, LoginUser};
use AppBundle\Form\{UserType, LoginType, ItemType};
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Frontend Controller class
 *
 */
class FrontendController extends Controller
{

    /**
     * Index
     *
     * @Route("/", name="frontend_index")
     *     
     * @param Request $request 
     */
    public function indexAction(Request $request)
    {
    
        $form = $this->createForm(UserType::class, new User(), [
                        'action' => $this->generateUrl('post_user'),
                        'method' => 'POST',
                    ]);

        return $this->render('AppBundle:Frontend:index.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Items
     * 
     * @Route("/items", name="frontend_items")
     *
     * @param Request $request 
     */
    public function itemsAction(Request $request)
    {
        $form = $this->createForm(ItemType::class, new Item(), [
                        'action' => $this->generateUrl('post_items'),
                        'method' => 'POST',
                    ]);

        return $this->render('AppBundle:Frontend:items.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Finish
     * 
     * @Route("/finish", name="frontend_finish")
     *
     * @param Request $request 
     */
    public function finish(Request $request)
    {
        return $this->render('AppBundle:Frontend:finish.html.twig',[]);
    }
    
    /**
     * Login
     * 
     * @Route("/login", name="frontend_login")
     *
     * @param Request $request 
     */
    public function login(Request $request)
    {
        $form = $this->createForm(LoginType::class, new LoginUser(), [
                        'action' => $this->generateUrl('login'),
                        'method' => 'POST',
                    ]);

        return $this->render('AppBundle:Frontend:login.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Logout
     * 
     * @Route("/logout", name="frontend_logout")
     *
     * @param Request $request     
     */
    public function logoutAction()
    {
        $this->get('security.token_storage')->setToken(null);
        
        return new RedirectResponse($this->generateUrl('frontend_index'));
    }
}

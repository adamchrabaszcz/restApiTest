<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FOSView;
use FOS\RestBundle\Controller\Annotations\{Post, Get, Patch};
use FOS\RestBundle\Util\Codes;
use AppBundle\Model\{User, LoginUser};
use AppBundle\Form\{UserType, LoginType};
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use JMS\Serializer;

class RestController extends Controller
{

    /**
     * @Post()
     * @View(statusCode=200)
     * @param Request $request    
     * @return Response    
     */
    public function postItemsAction(Request $request)
    {
        $session = $this->get('session');        
        $user = $session->get('user');  
        
        $client = $this->get('mothership.client');
        $suggestions = $client->selectCompanyUniqueIdSuggestions($user->getToken(), $request->getContent());
        
        foreach ($suggestions as $id => $options) {
            $suggestions[$id]['link'] = $this->generateUrl('get_item', ['id' => $options['unique_id']]);
        }
        
        return json_encode($suggestions);
    }
    
    /**
     * @Get()
     * @param int $id
     * @return Response    
     */
    public function getItemAction($id)
    {
        $session = $this->get('session');        
        $user = $session->get('user');  
        
        $client = $this->get('mothership.client');

        $company = $client->selectCompanyUniqueId($user->getToken(), $id);
        
        return $this->get('router')->generate('frontend_finish');
    }
    
    /**
     * @Post()
     * 
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true, serializerGroups={"post"})
     * @param Request $request    
     * @return Response
     */
    public function postUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user, ["method" => $request->getMethod()]);
        $form->handleRequest($request);        
        
        if ($form->isValid()) {
 
            $client = $this->get('mothership.client');
            $token = $client->signUp($user);
            
            $user->setToken($token);
            
            $session = $this->get('session');
            $session->set('user', $user);

            return $this->get('router')->generate('frontend_items');
            
        }

        return FOSView::create(['errors' => $form->getErrors()], 400);
    }
    
    /**
     * @Post()
     * @View(statusCode=200)
     * @param Request $request    
     * @return Response    
     */
    public function loginAction(Request $request)
    {
        $user = new LoginUser();
        $form = $this->createForm(new LoginType(), $user, ["method" => $request->getMethod()]);

        $form->handleRequest($request);        
        if ($form->isValid()) {
            
            $client = $this->get('mothership.client');
            
            if ($response = $client->logIn($user)) {
                return FOSView::create(['errors' => $response], 401);
            }
            
            return 'Logged in.';
            
        }

        return FOSView::create(['errors' => $form->getErrors()], 400);
    } 
    


}
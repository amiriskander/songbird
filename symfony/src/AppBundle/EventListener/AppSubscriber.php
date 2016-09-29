<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use AppBundle\Entity\UserLog;

class AppSubscriber implements EventSubscriberInterface
{
    protected $container;

    /**
     * AppSubscriber constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) // this is @service_container
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
    	// return the subscribed events, their methods and priorities
        return array(
	        EasyAdminEvents::PRE_NEW => 'checkUserRights',
            EasyAdminEvents::PRE_LIST => 'checkUserRights',
            EasyAdminEvents::PRE_EDIT => 'checkUserRights',
            EasyAdminEvents::PRE_SHOW => 'checkUserRights',
	        EasyAdminEvents::PRE_DELETE => 'checkUserRights',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'redirectUserAfterPasswordReset',
            KernelEvents::REQUEST => 'onKernelRequest'
        );
    }

    /**
     * show an error if user is not superadmin and tries to manage restricted stuff
     *
     * @param GenericEvent $event event
     * @return null
     * @throws AccessDeniedException
     */
    public function checkUserRights(GenericEvent $event)
    {

        // if super admin, allow all
	    $authorization = $this->container->get('security.authorization_checker');
        $request = $this->container->get('request_stack')->getCurrentRequest()->query;

	    if ($authorization->isGranted('ROLE_SUPER_ADMIN')) {
            return;
        }

        $entity = $request->get('entity');
        $action = $request->get('action');
        $user_id = $request->get('id');

        // This is an exception, allow user to view and edit their own profile irregardless of permissions
        if ($entity == 'User') {
            // if edit and show
            if ($action == 'edit' || $action == 'show') {
                // check user is himself
                if ($user_id == $this->container->get('security.token_storage')->getToken()->getUser()->getId()) {
                    return;
                }
            }
        }

	    $config = $this->container->get('easyadmin.config.manager')->getBackendConfig();

        // check for permission for each action
	    foreach ($config['entities'] as $k => $v) {
	    	if ($entity == $k && !$authorization->isGranted($v[$action]['role'])) {
			    throw new AccessDeniedException();
		    }
	    }
    }

    /**
     * Redirect user to another page after password reset is success
     *
     * @param  Configure $event GetResponseUserEvent
     * @return null
     */
    public function redirectUserAfterPasswordReset(FormEvent $event)
    {
        $url = $this->container->get('router')->generate('dashboard');
        $event->setResponse(new RedirectResponse($url));
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

    	$request = $event->getRequest();
        $current_url = $request->server->get('REQUEST_URI');
        // ensures we track admin only.
        $admin_path = $this->container->getParameter('admin_path');

        // only log admin area and only if user is logged in. Dont log search by filter
        if (!is_null($this->container->get('security.token_storage')->getToken()) && preg_match('/\/'.$admin_path.'\//', $current_url)
            && ($request->query->get('filter') === null) && !preg_match('/\/userlog\//', $current_url)) {

            $em = $this->container->get('doctrine.orm.entity_manager');
            $log = new UserLog();
            $log->setData(json_encode($request->request->all()));
            $log->setUsername($this->container->get('security.token_storage')->getToken()->getUser()
                ->getUsername());
            $log->setCurrentUrl($current_url);
            $log->setReferrer($request->server->get('HTTP_REFERER'));
            $log->setAction($request->getMethod());
            $log->setCreated(new \DateTime('now'));
            $em->persist($log);
            $em->flush();
        }
    }
}
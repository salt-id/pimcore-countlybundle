<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 09/02/2020
 * Time: 14:54
 */

namespace SaltId\CountlyBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\User as PimcoreUser;
use Pimcore\Tool\Session;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractController extends FrontendController
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $session = Session::getReadOnly();
        $user = $session->get('user');
        $permissions = $user->permissions;
        $hasExtensionPermission = false;

        if (getenv('PIMCORE_ENVIRONMENT') === 'dev') {
            return;
        }

        if (!$user instanceof PimcoreUser) {
            throw new HttpException('401', 'NO NO NO AUTH ');
        }

        $hasExtensionPermission = $user->isAdmin() ? true : in_array('access_extensions', $permissions, false);

        if (!$hasExtensionPermission) {
            throw new HttpException('401', 'Sorry you don\'t have permission to access the extension');
        }

        parent::onKernelController($event);
    }

}
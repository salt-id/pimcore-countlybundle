<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 09/02/2020
 * Time: 19:57
 */

namespace SaltId\CountlyBundle\EventListener;

use Pimcore\Bundle\CoreBundle\EventListener\Traits\PimcoreContextAwareTrait;
use Pimcore\Http\Request\Resolver\PimcoreContextResolver;
use Pimcore\Templating\Helper\HeadScript;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DocumentListener implements EventSubscriberInterface
{
    use PimcoreContextAwareTrait;

    /** @var HeadScript $headScript */
    private $headScript;

    public function __construct(HeadScript $headScript)
    {
        $this->headScript = $headScript;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest'],
        ];
    }

    public function onKernelRequest(GetResponseEvent $getResponseEvent)
    {
        $request = $getResponseEvent->getRequest();

        if (!$this->matchesPimcoreContext($request, PimcoreContextResolver::CONTEXT_DEFAULT)) {
            return;
        }

        $countlyAppKey = getenv("COUNTLY_APP_KEY", null);

        $this->headScript->offsetSetFile(-1, '/bundles/countly/js/front-end/countly.js');
        $this->headScript->offsetSetFile(-2, '/bundles/countly/js/front-end/countly.init.js');
        $this->headScript->offsetSetFile(-3, 'https://cdnjs.cloudflare.com/ajax/libs/countly-sdk-web/19.8.0/countly.min.js');
        $this->headScript->offsetSetScript(-4, 'var countlyAppKey = "' .$countlyAppKey . '"');
    }
}
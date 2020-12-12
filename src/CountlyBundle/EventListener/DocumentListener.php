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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DocumentListener implements EventSubscriberInterface
{
    use PimcoreContextAwareTrait;

    public const COUNTLY_BUNDLE_ACTIVE_KEY = 'COUNTLY_BUNDLE_ACTIVE';

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

        if (!$this->isActive($request)) {
            return;
        }

        $countlyAppKey = getenv("COUNTLY_APP_KEY", null);
        $environment = getenv('PIMCORE_ENVIRONMENT');

        $this->headScript->offsetSetFile(-1, '/bundles/countly/js/front-end/countly.js');
        if ($environment === 'dev') {
            $this->headScript->offsetSetFile(-2, '/bundles/countly/js/front-end/countly.init.development.js');
        }
        if ($environment === 'prod') {
            $this->headScript->offsetSetFile(-2, '/bundles/countly/js/front-end/countly.init.production.js');
        }
        $this->headScript->offsetSetFile(-3, 'https://cdn.jsdelivr.net/npm/countly-sdk-web@latest/lib/countly.min.js');
        $this->headScript->offsetSetScript(-4, 'var countlyAppKey = "' .$countlyAppKey . '"');
    }

    private function isActive(Request $request) : bool
    {
        return $request->get(self::COUNTLY_BUNDLE_ACTIVE_KEY, true);
    }
}

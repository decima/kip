<?php


namespace App\Controller;


use App\Annotations\RouteExposed;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class RoutingController extends AbstractController
{
    /**
     *
     * @Route("/_routing_js", name="routing_js")
     * @param RouterInterface $router
     * @param Reader $annotations
     * @RouteExposed()
     * @return Response
     * @throws ReflectionException
     */
    public function jsRoutingAction(RouterInterface $router, Reader $annotations)
    {
        /** @var $collection RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();
        $routes = [];
        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params) {
            $defaults = $params->getDefaults();
            if (isset($defaults['_controller'])) {
                $controllerAction = explode('::', $defaults['_controller']);
                if (class_exists($controllerAction[0])) {
                    $reflection = new ReflectionMethod($controllerAction[0], $controllerAction[1]);
                    if ($annotations->getMethodAnnotation(
                            $reflection,
                            RouteExposed::class
                        ) || $annotations->getClassAnnotation(
                            new ReflectionClass($controllerAction[0]),
                            RouteExposed::class
                        )
                    ) {
                        preg_match_all("/{([^\}]*)}/", $params->getPath(), $output_array);
                        $arguments = [];
                        foreach ($output_array[1] ?? [] as $k => $argument) {
                            $arguments[$argument] = $argument;
                        }
                        $routes[$route] = [
                            "name" => $route,
                            "path" => $params->getPath(),
                            "arguments" => array_map(static function ($item) use ($params) {
                                return $params->hasDefault($item) ? $params->getDefault($item) : null;
                            }, $arguments),
                        ];
                    }
                }
            }
        }

        /**
         * @var Response
         */
        $response = $this->render("utils/routing.js.twig", ["routes" => $routes]);
        if (getenv("APP_ENV") != "dev") {
            $response->setMaxAge(86400);
        }

        return $response;
    }

}

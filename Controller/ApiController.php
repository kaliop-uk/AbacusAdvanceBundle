<?php

namespace Abacus\AdvanceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Abacus\AdvanceBundle\Api\Service\CategoryListerInterface;
use Abacus\AdvanceBundle\Api\Service\StoryCategoryMapperInterface;
use Abacus\AdvanceBundle\Core\Response\Xml\Categories;
use Abacus\AdvanceBundle\Core\Response\Xml\StoryCategoryMapping;
use Abacus\AdvanceBundle\DependencyInjection\AbacusAdvanceExtension;

class ApiController extends Controller
{
    const ACTION_CATEGORY_LIST = 'categories';
    const ACTION_STORY_MAPPING = 'story_category_mapping';

    /**
     * Accessed by Advance via a callback, to retrieve the list of categories
     * @param string $site
     * @return Response
     */
    public function categoriesAction($site)
    {
        $actionService = $this->getActionService($site, self::ACTION_CATEGORY_LIST);
        if (!$actionService instanceof CategoryListerInterface) {
            /// @todo log error
            throw $this->createNotFoundException();
        }

        $xml = new Categories();
        foreach ($actionService->getAllCategories() as $market) {
            $xml->addCategory($market);
        }

        $response = $xml->toResponse();
        $actionService->preResponse($response);

        return $response;
    }

    /**
     * Accessed by Advance via a callback, to retrieve the list of categories that a Story belongs to
     * @param Request $request
     * @param string $site
     * @return Response
     */
    public function storyCategoryMappingAction(Request $request, $site)
    {
        $actionService = $this->getActionService($site, self::ACTION_STORY_MAPPING);
        if (!$actionService instanceof StoryCategoryMapperInterface) {
            /// @todo log error
            throw $this->createNotFoundException();
        }

        $contentId = trim($request->query->get('storycode'));
        if (empty($contentId)) {
            throw $this->createNotFoundException();
        }

        $story = $actionService->findStory($contentId);
        if (!$story) {
            throw $this->createNotFoundException();
        }

        $xml = new StoryCategoryMapping($story);
        $response = $xml->toResponse();
        $actionService->preResponse($response, $contentId);

        return $response;
    }

    /**
     * @param string $site
     * @param string $action
     * @return mixed
     */
    private function getActionService($site, $action)
    {
        $serviceParameter = sprintf(AbacusAdvanceExtension::API_PARAMETER_PATTERN, $site, $action);
        if (!$this->container->hasParameter($serviceParameter)) {
            return null;
        }

        $serviceId = $this->getParameter($serviceParameter);

        return !empty($serviceId) && $this->has($serviceId) ? $this->get($serviceId) : null;
    }
}

<?php

namespace App\Menu;



use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use App\Service\Category\CategoryCurrent;


class RequestVoter implements VoterInterface
{
    private $requestStack;
    private $categoryService;

    public function __construct(RequestStack $requestStack, CategoryCurrent $c)
    {
        $this->requestStack = $requestStack;
        $this->categoryService = $c;
    }

    public function matchItem(ItemInterface $item): ?bool
    {
     
        if($this->categoryService->getCategoryId()  != null && str_contains($item->getUri(), $categoryPatern)){
            return true;
        }

        $request = $this->requestStack->getCurrentRequest();
        if ($item->getUri() === $request->getRequestUri()) {
           
            return true;
        } else if ($item->getUri() !== $request->getBaseUrl() . '/' 
            && substr($request->getRequestUri(), 0, strlen($item->getUri())) === $item->getUri()) {
          
            return true;
        }

        return null;
    }
}
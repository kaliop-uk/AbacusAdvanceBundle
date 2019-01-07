<?php

namespace Abacus\AdvanceBundle\Controller\AdvanceStub;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SiteController
{
    public function adfeTokenAuthAction(Request $request)
    {
        return new RedirectResponse($request->query->get('returnUrl'));
    }
}

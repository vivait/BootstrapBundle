<?php

namespace Vivait\BootstrapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
	/**
	 * @param Request $request
	 * @param bool $avoid_loop Avoid redirecting back to the current page
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function redirectBack(Request $request, $avoid_loop = false) {
		$current = $request->attributes->get('_route');
		$parent = $request->query->get('parent', $request->request->get('parent', $request->headers->get('referer')));

		if (!$parent || ($avoid_loop && $parent == $current)) {
			$parent = $this->generateUrl('viva_app_homepage');
		}

		if ($request->headers->get('X-REQUESTED-WITH') == 'XMLHttpRequest') {
			return $this->render('VivaitBootstrapBundle:Default:redirect.html.twig', array(
				'redirect' => $parent
			));
		}

		return $this->redirect($parent);
	}
}

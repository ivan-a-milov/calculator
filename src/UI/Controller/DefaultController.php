<?php

declare(strict_types=1);

namespace Calculator\UI\Controller;

use Calculator\UI\Form\Dto\CalculatorDto;
use Calculator\UI\Form\CalculatorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CalculatorType::class);
        $form->handleRequest($request);

        $templateParameters = [
            'form' => $form->createView(),
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CalculatorDto $formData */
            $formData = $form->getData();
            $result = $formData->getOperation()->apply(
                $formData->getFirstArgument(),
                $formData->getSecondArgument()
            );

            $templateParameters['firstArgument'] = $formData->getFirstArgument();
            $templateParameters['operation'] = $formData->getOperation()->sign();
            $templateParameters['secondArgument'] = $formData->getSecondArgument();
            $templateParameters['result'] = $result;
        }

        return $this->render('index.html.twig', $templateParameters);
    }
}

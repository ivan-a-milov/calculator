<?php

declare(strict_types=1);

namespace Calculator\UI\Form;

use Calculator\Exception\InvalidArgumentsException;
use Calculator\Exception\InvalidOperationException;
use Calculator\Exception\NoSuchOperationException;
use Calculator\UI\Form\Dto\CalculatorDto;
use Calculator\Infrastructure\OperationFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalculatorType extends AbstractType implements DataMapperInterface
{
    public function __construct(
        readonly private OperationFactory $operationFactory,
        readonly private CalculatorDtoFactory $dtoFactory,
    )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstArgument', TextType::class)
            ->add('operation', ChoiceType::class, [
                'choices'  => $this->getOperationChoices(),
            ])
            ->add('secondArgument', TextType::class)
        ;
        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', CalculatorDto::class);
        $resolver->setDefault('empty_data', function (FormInterface $form) {
            try {
                return $this->formsToData([
                    'firstArgument' => $form->get('firstArgument'),
                    'operation' => $form->get('operation'),
                    'secondArgument' => $form->get('secondArgument'),
                ]);
            } catch (InvalidOperationException $exception) {
                $form->addError(new FormError($exception->getMessage()));
            }
       });
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms)
    {
        if ($viewData === null) {
            return null;
        }

        if (!$viewData instanceof CalculatorDto) {
            throw new UnexpectedTypeException($viewData, CalculatorDto::class);
        }

        $forms = iterator_to_array($forms);

        $forms['firstArgument'] = strval($viewData->getFirstArgument());
        $forms['operation'] = $viewData->getOperation()->sign();
        $forms['secondArgument'] = strval($viewData->getSecondArgument());
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData)
    {
        if ($viewData === null) {
            return null;
        }

        $forms = iterator_to_array($forms);
        $data = $this->formsToData($forms);
        /** @var CalculatorDto $viewData */
        $viewData
            ->setFirstArgument($data->getFirstArgument())
            ->setOperation($data->getOperation())
            ->setSecondArgument($data->getSecondArgument())
        ;
    }

    private function getOperationChoices(): array
    {
        $signs =  $this->operationFactory->getAllSigns();
        $operationChoices = [];
        foreach ($signs as $sign) {
            $operationChoices[$sign] = $sign;
        }

        return $operationChoices;
    }

    private function formsToData($forms): ?CalculatorDto
    {
        try {
            return $this->dtoFactory->createDto(
                $forms['firstArgument']->getData(),
                $forms['operation']->getData(),
                $forms['secondArgument']->getData(),
            );
        } catch (InvalidArgumentsException $exception) {
            foreach ($exception->getFields() as $field) {
                $forms[$field]->addError(new FormError('Invalid argument'));
            }
        } catch (NoSuchOperationException $exception) {
            $forms['operation']->addError(new FormError($exception->getMessage()));
        }

        return null;
    }
}

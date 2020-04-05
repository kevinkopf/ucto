<?php

namespace App\Service;

use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class VueUtils
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    public function extractProps(FormInterface $form): array
    {
        $vars = $form->createView()->vars;

        $props = [
            'id' => $vars['id'],
            'name' => $vars['full_name'],
            'serverError' => $this->getFirstError($vars['errors']),
        ];

        if (in_array('text', $vars['block_prefixes'])) {
            $props['initialValue'] = $vars['value'];
        }

        if (in_array('hidden', $vars['block_prefixes'])) {
            $props['initialValue'] = $vars['value'];
        }

        if (in_array('checkbox', $vars['block_prefixes'])) {
            $props['initialValue'] = $vars['checked'];
        }

        if (in_array('datetime', $vars['block_prefixes'])) {
            $props['initialValue'] = $vars['data'] ? $vars['data']->getTimestamp():null;
        }

        if (in_array('collection', $vars['block_prefixes'])) {
            $props['initialValue'] = (is_array($vars['value'])) ? array_values($vars['value']) : [];
        }

        if (in_array('choice', $vars['block_prefixes']) && ($vars['multiple'] === true || ($vars['multiple'] === false && $vars['expanded'] === false))) {
            $props['initialValue'] = (is_array($vars['value'])) ? array_values($vars['value']) : $vars['value'];
            $props['options'] = $this->encodeSelectChoices($vars['choices'], $vars);
        }

        if (in_array('choice', $vars['block_prefixes']) && $vars['multiple'] === false && $vars['expanded'] === true) {
            $props['initialValue'] = $vars['value'];

            $props['options'] = array_values(
                array_map(
                    function (FormView $choice) use ($vars) {
                        if (!$vars['translation_domain']) {
                            return [
                                'value' => $choice->vars['value'],
                                'id' => $choice->vars['id'],
                                'name' => $choice->vars['full_name'],
                                'text' => $choice->vars['label'],
                            ];
                        }

                        return [
                            'value' => $choice->vars['value'],
                            'id' => $choice->vars['id'],
                            'name' => $choice->vars['full_name'],
                            'text' => $this->translator->trans($choice->vars['label'], [], $vars['translation_domain']),
                        ];

                    },

                    iterator_to_array($form->createView())
                )
            );
        }

        return $props;
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    public function encodeProps(FormInterface $form): string
    {
        return json_encode(
            $this->extractProps($form)
        );
    }

    /**
     * @param FormInterface $collection
     * @return string
     */
    public function encodeContextFieldCollection(FormInterface $collection): string
    {
        $result = [];

        /** @var FormInterface $introduction */
        foreach ($collection as $language => $item) {
            $result[$language] = $this->extractProps($item);
        }

        return json_encode($result);
    }

    /**
     * @param FormErrorIterator $errors
     * @return string
     */
    private function getFirstError(FormErrorIterator $errors): string
    {
        return isset($errors[0]) ? $errors[0]->getMessage() : '';
    }

    /**
     * Encodes choiceView for selectbox. Method uses recursion to deal with "grouped" selects
     *
     * @param array $choices
     * @param $vars
     * @return array
     */
    private function encodeSelectChoices(array $choices, $vars) {

        $props = [];

        foreach ($choices as $choice) {

            // dealing with grouped choices
            if  ($choice instanceof ChoiceGroupView) {
                $props[] = [
                    'value' => $this->encodeSelectChoices($choice->choices, $vars),
                    'text' => $this->translator->trans($choice->label, [], $vars['translation_domain'])
                ];
                continue;
            }

            //dealing with one choice
            if (!$vars['translation_domain']) {
                $props[] = [
                    'value' => $choice->value,
                    'text' => $choice->label,
                ];
            } else {
                $props[] = [
                    'value' => $choice->value,
                    'text' => $this->translator->trans($choice->label, [], $vars['translation_domain']),
                ];
            }
        }

        return $props;
    }
}

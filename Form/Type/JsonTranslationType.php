<?php

namespace EasternColor\JsonTransBundle\Form\Type;

use EasternColor\CoreBundle\Form\Type\AbstractType;
use EasternColor\CoreBundle\Form\Type\CkEditorWwwType;
use Stringy\Stringy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class JsonTranslationType extends AbstractType
{
    const FIELDSGROUP_ITEM__NAME = 'item_name';
    const FIELDSGROUP_ITEM__TITLE = 'item_title';
    const FIELDSGROUP_ITEM__NAME_SHORTDESC_DESC = 'item_desc_3';
    const FIELDSGROUP_SEO__TITLE_META = 'seo';
    const FIELDSGROUP_GMAP__TITLE_DESC = 'gmap_title_desc';

    protected static $fieldsGroupMapping = [
        self::FIELDSGROUP_ITEM__NAME => [
            'name' => [],
        ],
        self::FIELDSGROUP_ITEM__TITLE => [
            'title' => [],
        ],
        self::FIELDSGROUP_ITEM__NAME_SHORTDESC_DESC => [
            'name' => [],
            'shortDescription' => ['field_type' => TextareaType::class],
            'description' => ['field_type' => CkEditorWwwType::class],
        ],
        self::FIELDSGROUP_SEO__TITLE_META => [
            'title' => [],
            'metaKeywords' => [],
            'metaDescription' => ['field_type' => TextareaType::class],
            'customMeta' => ['field_type' => TextareaType::class],
        ],
        self::FIELDSGROUP_GMAP__TITLE_DESC => [
            'title' => [],
            'description' => ['field_type' => TextareaType::class],
        ],
    ];

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getFieldsGroups()
    {
        return [static::FIELDSGROUP_ITEM__NAME, static::FIELDSGROUP_ITEM__NAME_SHORTDESC_DESC, static::FIELDSGROUP_SEO__TITLE_META, static::FIELDSGROUP_GMAP__TITLE_DESC];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // /* @var $site Site */
        // $site = $this->container->get('eastern_color_ec_site.doctrine.one_site.listener.controller')->getSite();
        // $locales = $site->getLocales();
        // $locale = $site->getLocaleDefault();
        $locales = ['en', 'zh'];
        $locale = 'en';

        $resolver->setDefaults([
            'label' => false,
            'compound' => true,
            'empty_data' => function (FormInterface $form) {
                return [];
            },
            'locales' => $locales,
            'default_locale' => $locale,
            'required_locales' => [],
            'fields_group' => null,
            'fields' => [],
            'default_locale_required_fields' => [],
            'exclude_fields' => [],
            'display_mode' => 'tabs',
            'translation_prefix' => '',
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->preBuildFormFieldsGroupProcess($builder, $options);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) use ($options) {
            $builder = $formEvent->getForm();

            foreach ($options['locales'] as $locale) {
                $builder->add($locale, null, ['label' => $locale,  'compound' => true, 'required' => false]);
                foreach ($options['fields'] as $fieldName => $fieldOptions) {
                    $formType = (isset($fieldOptions['field_type'])) ? $fieldOptions['field_type'] : null;
                    $fieldOptions['translation_domain'] = isset($fieldOptions['translation_domain']) ? $fieldOptions['translation_domain'] : $options['translation_domain'];
                    unset($fieldOptions['field_type']);
                    $builder->get($locale)->add(
                        $fieldName,
                        $formType,
                        $fieldOptions + (($options['default_locale'] === $locale and in_array($fieldName, $options['default_locale_required_fields'])) ? ['constraints' => [new Assert\NotBlank()], 'required' => true] : [])
                    );
                }
            }
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) use ($options) {
            $form = $formEvent->getForm();

            $result = [];
            foreach ($options['locales'] as $locale) {
                $result[$locale] = [];
                foreach ($options['fields'] as $fieldName => $fieldOptions) {
                    $result[$locale][$fieldName] = $form->get($locale)->get($fieldName)->getData();
                }
            }
            $form->setData($result);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormView      $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array                                 $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['default_locale'] = $options['default_locale'];
        $view->vars['required_locales'] = $options['required_locales'];
        $view->vars['display_mode'] = $options['display_mode'];
    }

    // public function getName()
    // {
    //     return $this->getBlockPrefix();
    // }
    //
    // public function getBlockPrefix()
    // {
    //     return 'a2lix_translations';
    // }

    protected function preBuildFormFieldsGroupProcess(FormBuilderInterface $builder, array &$options)
    {
        $result = [];
        if (isset($options['fields_group'])) {
            $fieldsGroupsToBeAdded = is_array($options['fields_group']) ? $options['fields_group'] : [$options['fields_group']];
            foreach ($fieldsGroupsToBeAdded as $fieldsGroup) {
                if (in_array($fieldsGroup, static::getFieldsGroups())) {
                    if (isset(static::$fieldsGroupMapping[$fieldsGroup])) {
                        foreach (static::$fieldsGroupMapping[$fieldsGroup] as $name => $field) {
                            $nameInSnakeCase = Stringy::create($name)->underscored();
                            $field['label'] = $options['translation_prefix'].'.admin.fields.json_trans.fields.'.$nameInSnakeCase.'.label';
                            unset($field['name']);
                            $result[$name] = $field;
                        }
                    }
                }
            }
        }
        foreach ($options['fields'] as $name => $field) {
            unset($field['name']);
            $result[$name] = (isset($result[$name]) ? $result[$name] : []) + $field;
        }

        $options['fields'] = $result;
    }
}

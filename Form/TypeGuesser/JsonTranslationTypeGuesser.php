<?php

namespace EasternColor\JsonTransBundle\Form\TypeGuesser;

use Doctrine\Common\Annotations\Reader;
use EasternColor\JsonTransBundle\Annotations\JsonTrans;
use EasternColor\JsonTransBundle\Form\Type\JsonTranslationType;
use ReflectionClass;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;

class JsonTranslationTypeGuesser implements FormTypeGuesserInterface
{
    /** @var string */
    protected $classShortName;

    /** @var JsonTrans */
    protected $classAnnotation;

    /** @var JsonTrans */
    protected $propertyAnnotation;

    /** @var Reader */
    protected $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function guessType($class, $property)
    {
        if ('jsonTrans' === $property) {
            $this->getAnnotations($class, $property, JsonTrans::class);
            if ($this->hasAnnotations()) {
                $defaultOption = [];
                if (null !== $this->classAnnotation) {
                    $defaultOption = $this->buildDefaultOptionByClassAnnotation();
                }
                $defaultOption['translation_prefix'] = strtolower($this->classShortName);

                return new TypeGuess(JsonTranslationType::class, $defaultOption, Guess::MEDIUM_CONFIDENCE);
            }
        }
    }

    public function guessRequired($class, $property)
    {
    }

    public function guessMaxLength($class, $property)
    {
    }

    public function guessPattern($class, $property)
    {
    }

    /**
     * @TODO validate the fields from the class annotation
     *
     * @return [type] [description]
     */
    protected function buildDefaultOptionByClassAnnotation()
    {
        return [
            'fields' => $this->classAnnotation->fields,
            'fields_group' => $this->classAnnotation->fieldsGroup,
        ];
    }

    protected function getAnnotations($class, $propertyName, $annotationFqcn)
    {
        $annotationReader = $this->annotationReader;

        $reflectionClass = new ReflectionClass($class);
        $reflectionProperty = null;
        $targetClass = $reflectionClass;

        do {
            $properties = $targetClass->getProperties();
            foreach ($properties as $property) {
                if ($propertyName === $property->getName()) {
                    $reflectionProperty = $property;
                    break;
                }
            }
            if (null !== $reflectionProperty) {
                break;
            }
            $targetClass = $targetClass->getParentClass();
        } while (null !== $targetClass and false !== $targetClass);

        $this->classShortName = $reflectionClass->getShortName();
        $this->classAnnotation = $annotationReader->getClassAnnotation($reflectionClass, $annotationFqcn);
        $this->propertyAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $annotationFqcn);
    }

    protected function hasAnnotations()
    {
        return null !== $this->classAnnotation or null !== $this->propertyAnnotation;
    }
}

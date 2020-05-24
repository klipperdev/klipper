Doctrine Callback
=================

The purpose of the Callback constraint is to create completely custom
validation rules and to assign any validation errors to specific fields on
your object. If you're using validation with forms, this means that you can
make these custom errors display next to a specific field, instead of simply
at the top of your form.

This process works by specifying one callback* method, each of which will be
called during the validation process. Each of those methods can do anything,
including creating and assigning validation errors.

> A callback method itself doesn't *fail* or return any value. Instead,
>  as you'll see in the example, a callback method has the ability to directly
>  add validator "violations".

|                |                                                                                     |
|----------------|-------------------------------------------------------------------------------------|
| **Applies to** | `class`                                                                             |
| **Options**    | <ul><li>[callback](#callback)</li></ul>                                             |
| **Class**      | `Klipper\Component\DoctrineExtensions\Validator\Constraints\Callback`               |
| **Validator**  | `Klipper\Component\DoctrineExtensions\Validator\Constraints\CallbackValidator`      |

## Configuration

### Yml

```yaml
# src/Acme/BlogBundle/Resources/config/validation.yml
Acme\BlogBundle\Entity\Author:
    constraints:
        - DoctrineCallback: [validate]
```

### PHP Annotations

```php
// src/Acme/BlogBundle/Entity/Author.php
namespace Acme\BlogBundle\Entity;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\ExecutionContextInterface;
use Klipper\Component\DoctrineExtensions\Validator\Constraints as Assert;

class Author
{
    /**
     * @Assert\DoctrineCallback
     */
    public function validate(ExecutionContextInterface $context, ManagerRegistry $registry)
    {
        // ...
    }
}
```

### XML

```xml
<!-- src/Acme/BlogBundle/Resources/config/validation.xml -->
<?xml version="1.0" encoding="UTF-8" ?>
<constraint-mapping xmlns="http://symfony.com/schema/dic/constraint-mapping"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/constraint-mapping http://symfony.com/schema/dic/constraint-mapping/constraint-mapping-1.0.xsd">

    <class name="Acme\BlogBundle\Entity\Author">
        <constraint name="DoctrineCallback">validate</constraint>
    </class>
</constraint-mapping>
```

### PHP

```php
// src/Acme/BlogBundle/Entity/Author.php
namespace Acme\BlogBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Klipper\Component\DoctrineExtensions\Validator\Constraints as Assert;

class Author
{
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new Assert\DoctrineCallback('validate'));
    }
}
```

## The Callback Method

The callback method is passed a special ``ExecutionContextInterface`` object and
``ManagerRegistry`` object. You can set "violations" directly on this object and
determine to which field those errors should be attributed:

```php
// ...
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\ExecutionContextInterface;

class Author
{
    // ...
    private $firstName;

    public function validate(ExecutionContextInterface $context, ManagerRegistry $registry)
    {
        $em = $registry->getManagerForClass(__CLASS__);
        // somehow you have an array of "fake names" from the database
        $fakeNames = $em->createQuery('your query')->getResult();

        // check if the name is actually a fake name
        if (in_array($this->getFirstName(), $fakeNames)) {
            $context->addViolationAt(
                'firstName',
                'This name sounds totally fake!',
                array(),
                null
            );
        }
    }
}
```

## Static Callbacks

You can also use the constraint with static methods. Since static methods don't
have access to the object instance, they receive the object as the first argument:

```php
// ...
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\ExecutionContextInterface;

class MyCustomClassValidation
{
    // ...
    public static function validate($object, ExecutionContextInterface $context, ManagerRegistry $registry)
    {
        $em = $registry->getManagerForClass(__CLASS__);
        // somehow you have an array of "fake names" from the database
        $fakeNames = $em->createQuery('your query')->getResult();

        // check if the name is actually a fake name
        if (in_array($object->getFirstName(), $fakeNames)) {
            $context->addViolationAt(
                'firstName',
                'This name sounds totally fake!',
                array(),
                null
            );
        }
    }
}
```

## Options

### callback

**type**: ``string``, ``array`` or ``Closure``

The callback option accepts three different formats for specifying the
callback method:

* A **string** containing the name of a concrete or static method;
* An array callable with the format ``array('<Class>', '<method>')``;
* A closure.

Concrete callbacks receive an :class:`Symfony\Component\Validator\ExecutionContextInterface`
instance as first argument, and the :class:`Doctrine\Persistence\ManagerRegistry`
instance as the second argument.

Static or closure callbacks receive the validated object as the first argument
and the :class:`Symfony\Component\Validator\ExecutionContextInterface`
instance as the second argument, and the :class:`Doctrine\Persistence\ManagerRegistry`
instance as the third argument.

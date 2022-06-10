SpecificationContainer
======================

The **SpecificationContainer** (which is required when utilizing the `DynamicFieldsType`) can be defined in many different
ways. The simplest method is to use the `AbstractSpecificationContainer` on the 'container' entity:

```php
#[ORM\Entity(repositoryClass: SurveyRepository::class)]
class Survey extends AbstractSpecificationContainer
{
    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: Question::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    #[Assert\Valid]
    private Collection $questions;

    // ...

    public function getFormSpecifications(array $params = []): array
    {
        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        $criteria->where($expressionBuilder->eq('active', $params['active']));

        return $this->getQuestions()->matching($criteria)->toArray();
    }
}
```

The **SpecificationContainer** could also be defined on a Repository class or even a local Provider class. In the end,
the only requirement is that it implement the `getFormSpecifications` method which must return a list of the needed
FormSpecifications.
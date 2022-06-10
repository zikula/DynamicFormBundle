Creating a sample implementation
================================

Let's create a real-world example - a Survey Maker!

1. `Survey extends AbstractSpecificationContainer`
    1. OneToMany Question
    2. OneToMany SurveyResponse
2. `Question extends AbstractFormSpecification`
    1. ManyToOne Survey
3. `SurveyResponse extends AbstractResponseData`
    1. ManyToOne Survey

Generate the required entities
- `symfony console make:entity Survey`
- `symfony console make:entity Question`
- `symfony console make:entity SurveyResponse`

- Adjust `Question extends AbstractFormSpecification`
- Adjust `Survey extends AbstractSpecificationContainer`
- Adjust `SurveyResponse extends AbstractResponseData`

You may need to go back and edit `Survey` to create the OneToMany relationships.

Standard "CRUD" Controller can be quickly generated to make much of the needed boilerplate code for a
sample implementation.

- `symfony console make:crud Survey`
- `symfony console make:crud SurveyResponse`

Adjustments to forms and controllers will be required to finalize the implementation.
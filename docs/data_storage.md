Data Storage
============

The entire collection of responses is stored in the `data` column of the entity that extends
`AbstractResponseData`. This data array is stored as a `json` serialized array. By default, the data is stored in
whatever format the FormType returns. In most situations, this is sufficient. I.e. a TextType response is stored as a
simple string field, multiple ChoiceType is stored as an array, DateType is stored as a serialized Date object.

The storage of values therefore can be altered by customizing your application's formTypes and utilizing `DataMappers`
or `DataTransformers` as appropriate for your business logic.

Provided Custom FormTypes
-------------------------
### ChoiceTypeTransformed
ChoiceTypeTransformed will store the _value_ of the selected choice(s). If multiple values are allowed, they are stored
as a comma-seperated string. If the label for the value is required, the application should employ an Enum or other
type of associating the value to the label.

### ChoiceWithOtherType
ChoiceWithOtherType will store the _value_of the selected choice(s), or the indicated 'other' value (or all of the
above) the same as ChoiceTypeTransformed, above.

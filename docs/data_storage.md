Data Storage
============

The general principle of data storage for dynamic properties is that they _must_ be a key:value store. Both the key
and the value are required to be simple datatypes (strings, ints).

The entire collection of properties is stored in the `data` column of the entity that extends
`AbstractDynamicPropertyData`. This data array is stored as a `json` serialized array. As stated above, this is a
_simple array_ of only one 'layer'. Each form field must store a textual representation of a simple value.

If the created application employing this bundle creates and adds custom fields, they should use `DataMappers` to
map the data to a simple value (e.g. *not* an array or object).

ChoiceTypes will store the _value_ of the selected choice(s). If multiple values are allowed, they are stored as a
comma-seperated string. If the label for the value is required, the application should employ an Enum or other
type of associating the value to the label.

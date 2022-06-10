Form groups
===========

Each created form specification can optionally be assigned to a **group**. This group can then be used in the display
of the forms in the template (for example in separate `fieldsets`). Because the group cannot be included in the
actual formType definition, they must be retrieved from the SpecificationContainer and then looped in the template to
display the fields as desired.

### Response form
Display the form fields by group:
```twig
{# templates/survey_response/_form.html.twig #}
{{ form_start(form) }}
{{ form_row(form.email) }}
{% for groupLabel, nameAndLabel in survey.groupedLabels %}
    <fieldset style="border:1px solid black;">
        <legend>{{ groupLabel }}</legend>
        {% for name, label in nameAndLabel %}
            {{ form_row(form.survey[name]) }}
        {% endfor %}
    </fieldset>
{% endfor %}
<button class="btn">{{ button_label|default('Save') }}</button>
{{ form_end(form) }}
```

### Response display
Display the responses by group:
```twig
{# templates/survey_response/show.html.twig #}
<table>
    <thead>
    <th>Name</th>
    <th>Value</th>
    </thead>
    <tbody>
    {% for groupLabel, nameAndLabel in survey_response.survey.groupedLabels %}
        <tr><td colspan="2"><strong>{{ groupLabel }}</strong></td></tr>
        {% for name, label in nameAndLabel %}
            <tr>
                <td>{{ label }}</td>
                <td>{{ attribute(survey_response, name)|default }}</td>
            </tr>
        {% endfor %}
    {% endfor %}
    </tbody>
</table>
```

Translation
-----------
If translation is enabled, the group name and labels can be translated to supported locales by indicating the desired
translation.

```twig
{# templates/survey_response/_form.html.twig #}
...
{% for groupLabel, nameAndLabel in survey.groupedLabels(app.request.locale) %}
...
```

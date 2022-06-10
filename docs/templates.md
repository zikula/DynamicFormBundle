Display in templates
====================

Display of 'creating' form
--------------------------
The display of the creating form (for the site admin) can be quite simple. In the example below, the `form.questions`
row are the dynamic fields.

```twig
{# templates/survey/_form.html.twig #}
{{ form_start(form) }}
    {{ form_row(form.questions) }}
{{ form_end(form) }}
```

Display of 'responding' form
----------------------------
Similarly, the display of the form to the user to respond can be quite simple:

```twig
{# templates/survey_response/_form.html.twig #}
{{ form_start(form) }}
    {{ form_widget(form) }}
    <button class="btn">{{ button_label|default('Save') }}</button>
{{ form_end(form) }}
```
For a more advanced display using groups, please see the [Groups](groups.md) doc.

Display of results
------------------
The display of individual responses is also simple, but requires the use of the `attribute` twig function.
```twig
{# templates/survey_response/show.html.twig #}
<table>
    <thead>
        <th>Name</th>
        <th>Value</th>
    </thead>
    <tbody>
    {% for name, label in survey_response.survey.labels %}
        <tr>
            <td>{{ label }}</td>
            <td>{{ attribute(survey_response, name)|default }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>
```
For a more advanced display using groups, please see the [Groups](groups.md) doc.

Translation
-----------
If translation is enabled, the labels can be translated to supported locales by indicating the desired translation.

```twig
{# templates/survey_response/show.html.twig #}
...
{% for name, label in survey_response.survey.labels(app.request.locale) %}
...
```

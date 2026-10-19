---
layout: default
title: Team
lang_root: en
lang_tag: en
lang_switch: /pt/team/
lang_switch_label: PT
permalink: /en/team/
---

{% assign role_order = "Group Leader|Project Manager|Postdocs|PhD students|Master Students|Undergrad students|Technicians|Former members" | split: "|" %}
{% assign team_list = site.data.team.members %}
{% if team_list == nil %}
  {% assign team_list = empty %}
{% endif %}

{% for r in role_order %}
  {% assign filtered = team_list | where: "role", r %}
  {% if filtered and filtered.size > 0 %}
    {% assign members = filtered | sort: "order" %}

## {{ r }}

<div class="cards">
{% for m in members %}
  <article class="card">
    {% if m.photo %}
      {% assign photo = m.photo | strip %}
      {% assign first = photo | slice: 0, 1 %}
      {% if first != "/" %}
        {% assign photo = "/" | append: photo %}
      {% endif %}
      {% if photo contains site.baseurl %}
        {% assign photo = photo | replace_first: site.baseurl, "" %}
      {% endif %}
      {% assign photo = m.photo | strip %}
      {% assign first = photo | slice: 0, 1 %}
      {% if first != "/" %}
        {% assign photo = "/" | append: photo %}
      {% endif %}
      {% if photo contains site.baseurl %}
        {% assign photo = photo | replace_first: site.baseurl, "" %}
      {% endif %}
      <img class="card-photo" src="{{ site.baseurl }}{{ photo }}" alt="">
    {% endif %}
    <div class="card-body">
      <h3 class="card-title">{{ m.name }}</h3>
      {% if m.title %}<p class="card-subtitle">{{ m.title }}</p>{% endif %}
      <p>{{ m.bio_en }}</p>
      {% if m.lattes %}
        <p><a href="{{ m.lattes }}" aria-label="CV Lattes of {{ m.name }}">Lattes</a></p>
      {% endif %}
    </div>
  </article>
{% endfor %}
</div>

  {% endif %}
{% endfor %}

{% if team_list == empty %}
<p>Coming soon.</p>
{% endif %}

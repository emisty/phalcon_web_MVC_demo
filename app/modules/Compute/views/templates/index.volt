<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
        <title>{% block title %}{% endblock %}</title>
        <meta name="description" content="{% block description %}{% endblock %}">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        {% block metaB %}{% endblock %}

        <!-- 重置 公用 框架css-->
        {{ stylesheet_link('') }}

       
    </head>

    <body class="page-loading">
        {#{ partial('shared/left') }#}
        {#{ partial('shared/header') }#}

        <!-- main area -->
        <div class="main-content" id="maincontent">
            <!-- 内容 -->
            {% block contentA %}{% endblock %}
            {{ content() }}
            {% block contentB %}{% endblock %}
            {#{ partial('shared/footer') }#}
            {#{ partial('shared/chat') }#}
        </div>

        <!-- build:js({.tmp,app}) scripts/app.min.js -->
        {{ javascript_include('') }}

    </body>


</html>
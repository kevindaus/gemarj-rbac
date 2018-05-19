
<style type="text/css">
    #dashboard_right_now li, #dashboard_quick_press li {
        width: 50%;
        float: left;
        margin-bottom: 10px;
    }
</style>
<h1>Gemarj RBAC Documentation</h1>
<p>
    This plugin allows user to restrict content by using a shortcode.
</p>

<hr>
<h2>
    Shortcode Usage
</h2>
<ol>
    <li>
        <pre>
            [gemarj-rbac role="editor"]
                Users with role of editor can see this content
            [/gemarj-rbac]
        </pre>
    </li>
    <li>
        <pre>
            [gemarj-rbac role="administrator,editor"]
                Users with role of editor or administrator can see this content
            [/gemarj-rbac]

        </pre>
    </li>

    <li>
        <pre>
            [gemarj-rbac capability="edit_post"]
                Users granted with capability of edit_post can see this content
            [/gemarj-rbac]
        </pre>
    </li>
    <li>
        <pre>
            [gemarj-rbac capability="delete_post,edit_post"]
                Users granted with capability of edit_post or delete_post can see this content
            [/gemarj-rbac]
        </pre>
    </li>
    <li>
        <pre>
            [gemarj-rbac role="editor" capability="edit_post"]
                Users with role of editor and granted with edit_post capability can see this content
            [/gemarj-rbac]
        </pre>
        <em><strong>Note : Role will be evaluated first then capability</strong></em>
    </li>
</ol>

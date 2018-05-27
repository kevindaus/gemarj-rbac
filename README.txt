=== Plugin Name ===
Contributors: Kevin Florenz Daus
Donate link: https://www.linkedin.com/in/kevinflorenzdaus/
Tags: rbac
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==

With this plugin you can restrict access to certain content
that only the users with certain role can access and view

== Key Features ==

Anyone with edit/create content access can restrict content
Can be used in any custom post type.

== Usage ==

`[gm-rbac role="selected_role"]`` shortcode allows for private content to be viewed by those who have the selected role.
`[gm-rbac errorMessage="Please login here . <a href='/wp-admin'>Login here</a>"]` On the fly cuztomizable error message.
[gm-rbac role="selected_role" capability="selected_capability" ] You can use role and capability combination to better filter the user that can access the content

== Filter ==

```gm_error_message_filter```
* Allow user to edit the enclosed content. You may want to add your custom filter to wrap the content with a custom class or id.

```
Example

add_filter("gm_error_message_filter" , function($errorMessage){
    $errorMessage = "<div class='my-custom-error-style-class'>".$errorMessage."</div>";
    return $errorMessage;//dont forget to return it
});

```

== Action ==

```gm_before_check_action```
* Allow user to attach an event handler before checking the current users' permission on the item enclosed by the shortcode.

```gm_before_render_action```
* Allow user to attach an event handler before rendering the content enclosed in the shortcode.

```gm_after_check_action```
* Allow user to attach an event handler after checking the current users' permission on the item enclosed by the shortcode.

== Shortcode order of execution ==
1. gm_before_check_action
1. gm_after_check_action
1. gm_before_render_action



== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Go to `https://github.com/kevindaus/gemarj-rbac/releases`
1. Download the latest release.
1. Upload the downloaded zip file by navigating to `/wp-admin/plugins.php`. Click `Add new` then click `Upload`
1. Wait until the plugin is uploaded then activate the plugin.
1. Done



== Changelog ==

= 1.0  Initial release .  =
* ADD - simple role editor
* ADD - simple role manager
* ADD - settings page to customize error message
* ADD - basic help page
* ADD - shortcode gemarj-rbac
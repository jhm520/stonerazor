header.php will be the very first file included in any page that will be navigated to.
Do not include it in any other file, (generally other scripts) or you will get
an error. Assume that all the functionality of this file is available to you when
writing any script.

On pages that will be navigated to, include header.php using REQUIRE, not
INCLUDE.

IT IS IMPORTANT TO DO THIS BEFORE ANY HTML WHITESPACE IS OUTPUTTED! This is because
header contains certain operations that don't work if there has been whitespace, such
as initiating the session, and in the case of the captcha, changing type to png.

At the top of every script, use REQUIRE_ONCE to include kill.php. Note that scripts in
the scripts directory are not autonomous, and if you remove kill.php they will not work
and possibly cause errors or fuck up the site if you execute them without knowing what
you're doing.

To reference files in php you can use $root which is defined in header.php. You don't
need it in html, css and javascript though.

At the top of every script, put a description and say where this script gets included.
Many will get included in header.php

Fill this in:


             |  Sam  |  John  |  Evan
-------------+-------+--------+---------
php          |  yes  |   yes  |
php objects  |   no  |        |
javascript   |  meh  |        |
jquery       |   no  |        |
mysql        |  yes  |        |
css          |  yes  |        |
html         |  yes  |        |
ajax         |  meh  |        |

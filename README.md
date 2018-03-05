EasternColorJsonTransBundle
=========================
This Symfony bundle provide entity trasnlation using single DB column.
** This is known as an anti-pattern to store translation in json format.

Installation
------------
1. `composer require eastern-color/json-trans-bundle`
2. Enable bundle in symfony's __/app/AppKernel.php__
    - `new EasternColor\JsonTransBundle\EasternColorJsonTransBundle()`,

Prerequisites
-------------

TODO
----
- Add configurable emtpy-fallback to translate between zh-hant and zh-hans
- Add routing to translate between zh-hant and zh-hans
- Extends this README

Command
-------

Basic Usage (route option)
--------------------------

Advanced Usage
-----


License
-------
This bundle is under the MIT license.

Depcore SVG Dimensions Plugin
=============================

Smart SVG dimension extraction for OctoberCMS — no database changes and fully Tailor compatible.

OctoberCMS automatically stores dimensions for raster images (JPG, PNG, GIF, WebP).
 However, SVG images do not store width/height in the database.
 This plugin provides runtime helpers to extract SVG dimensions directly from the file.


✨ Features
----------
*   Reads width and height attributes from SVG files
*   Fallback to viewBox when width/height are missing
*   Does not modify system\_files
*   Works with CMS, components, plugins, and Tailor
*   Twig helpers:
    *   svg\_dimensions(file)
    *   image\_dimensions(file)
*   Lightweight and dependency-free

📦 Installation
---------------

### Composer
composer require depcore/svgdimensions-plugin

### Manual
Copy the plugin to:
plugins/depcore/svgdimensions/

🧠 Usage
--------

### Universal image dimensions (SVG + raster)
{% set dims = image\_dimensions(record.icon) %}
{% if dims %}
    {{ dims.width }} × {{ dims.height }}
{% endif %}

### SVG-only dimensions
{% set svg = svg\_dimensions(record.icon) %}
{% if svg %}
    {{ svg.width }} × {{ svg.height }}
{% endif %}

### Returned structure
\[
    "width"  => 120,
    "height" => 120
\]

Returns null if no dimensions can be detected.

🛠 Tailor Integration
---------------------

Tailor blueprint:

fields:

    icon:

        label: Icon

        type: fileupload

        mode: image

        maxFiles: 1



Twig:

{% set dims = image\_dimensions(record.icon) %}

{% if dims %}

    <span>{{ dims.width }} × {{ dims.height }}</span>

{% endif %}





* * *



📂 Directory Structure
----------------------

plugins/

└─ depcore/

   └─ svgdimensions/

      ├── Plugin.php

      ├── composer.json

      ├── README.md

      └── CHANGELOG.md





* * *



🔍 How It Works
---------------

### Raster images

OctoberCMS exposes their width/height directly from System\\Models\\File.

### SVG images

OctoberCMS does not extract their dimensions.

This plugin:

1.  Detects if the file is an SVG


2.  Reads and parses the file


3.  Extracts width, height, or viewBox values


4.  Returns clean integer dimensions



All at runtime.
 No database writes, no schema changes.



* * *



📸 Example Output
-----------------
64 × 64
256 × 240
1024 × 512


🔧 Twig Functions
-----------------
Function
Description
svg\_dimensions(file)
Returns dimensions for SVGs only
image\_dimensions(file)
Returns dimensions for both SVG and raster




❗ Troubleshooting
-----------------

### Returns float instead of integer

All dimensions are cast to integers.
 If needed in Twig:

{{ dims.width|round(0) }}


### Returns null

Possible causes:

*   File is not an SVG
*   SVG lacks both width/height and viewBox
*   Invalid or corrupted SVG


🛡 Requirements
---------------

*   PHP 8.0+
*   OctoberCMS 3.x


📄 License
----------

MIT License.


👤 Author
---------

Depcore
 [https://depcore.pl](https://depcore.pl)



🤝 Contributing
---------------

Pull requests and issues are welcome.


🗒 Changelog
------------

See CHANGELOG.md.
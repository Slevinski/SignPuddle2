# SignPuddle 2
Create dictionaries and documents for sign languages using SignWriting

## Motivation
SignPuddle 2 continues to be useful for people worldwide.
For their benefit, the source code is now open source.

SignPuddle 2 was developed over time.
There are many layers of encoding and technologies.

Later this year, we are moving the SignWriting websites to a new server.
There are several design issues with SignPuddle 2 that will negatively affect the performance of the new server.
These issues will be addressed.

Going forward, the community will be able to add additional improvements.

## Installation
To install SignPuddle 2, you will need a PHP server.
* ImageMagick
* SQLite 3

Unzip iswa.zip to iswa.sql3

Default login:
* user - admin
* password - admin

## Changes
### Use TrueType Font
The glyph and glyphogram scripts are data intensive and wasteful.
These scripts wll be removed and replaced with images from the TrueType Fonts.

### Use SQLite or MySQL databases
The XML backend for the dictionaries and documents is memory intensive and wasteful.
The backend will be rewritten to use a database instead.
The SignPuddle Markup Language (SPML) will be available as an export option only.

### Use POST for Large Data
Certain pages use long URLs to add data.
This will cause data loss without changes in PHP.ini to support.
These pages will need to be updated to use POST data rather than the URL.

## License
MIT

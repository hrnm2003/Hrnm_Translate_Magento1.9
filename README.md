# Hrnm Translate Module

## Overview

`Hrnm_Translate` is a custom Magento 1.9 module developed to extend the template filtering system and add support for a new `{{trans}}` directive within CMS content (blocks, pages, emails, etc.). This directive enables easy localization and translation of strings directly within template content.

This module allows developers and content managers to define translatable strings with optional dynamic parameters and modifiers such as escaping HTML or rendering raw HTML.

## Features
* Adds a new template directive: `{{trans}}`
* Supports dynamic variables in translatable strings
* Includes modifier support (`escape`, `raw`, etc.)
* Fallbacks to default Magento translation mechanisms (`__()` function)

## Installation
Follow the steps and do not forget to back up your database and files.

**1. Download the extension**
   * Download ZIP file or clone the github.
   
      ```bash
      git clone https://github.com/hrnm2003/assembly-calculator.git
      ```

**2. Upload Files To The Root Directory**
   * Extract or move files to the root directory.
   * Merge folders carefully (do NOT overwrite files).   

**3. Set Correct Permissions**
   * Make sure uploaded files have appropriate permissions.

**4. Clear Cache**
   * Go to Admin > System > Cache Management and flush Magento cache and reindex data if required.
   * Or use following command:
   ```bash
      rm -rf var/cache/*
      rm -rf var/session/*
   ```

## Usage
To provide translations, add entries to your locale CSV file.

1. Navigate to Page or Block to add a translation:

   `Admin Panel → CMS → Pages` or `Blocks → Add New`

2. Add the content editor (e.g.):

   ```html
   <h2>{{trans "Welcome to our store!"}}</h2>
   <p>{{trans "Hello, %name!" name="$customerName"}}</p>
   <p>{{trans "This is <b>bold</b> text."|raw}}</p>
   ```

> Tip: You can also set `$customerName` via layout XML or block configuration.

3. Edit or create translation CSV file:

   **Presian File Location:**
   
   `app/locale/fa_IR/translate.csv`

   ```csv
   "Welcome to our store!","به فروشگاه ما خوش آمدید!"
   "Hello, %name!","سلام، %name!"
   "This is <b>bold</b> text.","این یک متن <b>برجسته</b> است."
   ```
   **French File Location:**
   
   `app/locale/fr_FR/translate.csv`

   ```csv
   "Welcome to our store!","Bienvenue dans notre magasin!"
   "Hello, %name!","Bonjour %name!"
   "This is <b>bold</b> text.","Il s'agit d'un texte en <b>gras</b>."
   ```

Make sure your store is configured for the right locale
> System → Configuration → General → Locale Options → Locale: `Choose Your Laaguage`



---
## Author
- **Hamidreza Nadi Moghadam**

Feel free to open an issue or submit a pull request if you encounter any problems or wish to suggest improvements.
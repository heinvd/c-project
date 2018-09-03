# C-project - For Rhino Africa

This here is a very basic handling of the messi file (format sample as received by Candice located in the "Use" folder.

When the file gets uploaded, I use PhpSpreadsheet installed via Composer using the following command from your root folder (if you do not know how to use composer, you should not be reading this.  Call someone that does)

```
composer require phpoffice/phpspreadsheet
```

## Important notes:

The system reports on the following:

- Publication Field
  - checks if it was provided
- Contact Number Field
  - changes it to a valid E164 Formatted number, and reports if it was provided
- Email Field
  - checks each supplied email address, (you can test by delimiting emailaddresses with either , or ;    
  - it also checks if the email address is in a valid format and it then checks if the domain exists and reports back on that.
- Join Date Field
  - Important
    - Assumes DD/MM/YYYY for dates where MM > 12
    - Assumes MM/DD/YYYY for dates where DD <= 12
    - Converts Excel Date (float) values
    
- The Secion header currently only assumes "Gauteng:","KZN" and "CPT"

- The /uploads folder needs to be writable


## Live Demo
You can check out the [Live Demo](https://www.webgiant.co.za/c-project) 

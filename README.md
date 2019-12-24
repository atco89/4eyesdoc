# 4EYESDOC

[![N|Solid](src/public/media/icons/navigation-icon.png)](http://www.4eyesdoc.rs)

Application for ophthalmic clinics. The program is capable of performing the following operations:

* Creating user profile;
* Managing users and their rights (privileges);
* Creating doctor work schedule and defining which examination may do;
* Opening patient card board;
* Editing the patient card board;
* Patient examination history;
* Downloading the examination parameters from history and past values into fields of new examination;
* Create examination report. There are six different reports with predefined fields and some values:
  * Daily curve
  * Computerized visual field test (CVF)
  * Low vision assessment (LVA)
  * Empty finding
  * Refraction
  * Specialist report
* Generate examination reports in PDF format;
* Exporting registers (examinations, card boards, work schedule, users) into excel file.

### Prerequisites

```
GIT
Apache web server
PHP 7.+ 
MySQL 5.7 +
Composer
```

## Getting Started

Before downloading [install git](https://git-scm.com/downloads) on you local machine. After first step open terminal (for example CMD) and position yourself in directory in which you want to install application. Download files into selected folder. For example:

```
C:\wamp\www> git clone https://github.com/atco89/4eyesdoc.git
```

### Installing

Step 1:

[Download composer](https://getcomposer.org/download/) into application root (4eyesdoc) folder and run next command in terminal, it will install dependencies for this project:

```
C:\wamp\www\4eyesdoc> php composer.phar install
```

Step 2:

Install database [tables](src/database/installation.sql) 

Step 3:
 
Install db [procedures](src/database/procedures.sql).

Step 4:

Create folder `sessions` in root (4eyesdoc) folder. 

FINISHED:

You may run application by typing http://localhost/4eyesdoc/public in your browser url field. 
If you are using some other port for your web server you need to type it too in you URL. 

You may login into application using:

- **username**: admin
- **password**: admin

## Built With

* [Slim 3](https://www.slimframework.com/) - PHP micro framework
* [Laravel Eloqunet](https://laravel.com/docs/5.7/eloquent) - Object-relational mapper
* [Twig](https://twig.symfony.com/) - Template engine for PHP
* [PHP Spreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) - Excel library
* [DOMPDF](https://github.com/dompdf/dompdf) - PDF library

## Versioning

1.0. - Initial version - first release 15.09.2018.

## Author

Aleksandar Rakić - *Initial work* - [www.aleksandarrakic.com](http://www.aleksandarrakic.com)
# A Spider Sahadan.com on PHP

This project was developed with php. No commercial use of the project is contemplated. I am waiting for your pull-request, watching and stars for this project. Everyone's support will play a bigger role in the development of this project.

# Usage

Step 1: Include the project

require_once('class.sahadan.php');

Step 2: Define the class

$sahadan = new Sahadan();

Last Step: List of Events

@return Array:
print_r($sahadan->events());

@return Object:
echo json_encode($sahadan->events());

# System Requirements

PHP >= 5.6

cURL Extension

# Contributing

If you would like to contribute to this project, please send your changes to the required site address. https://github.com/orhanbhr/sahadan.com-bot-2017/pulls
